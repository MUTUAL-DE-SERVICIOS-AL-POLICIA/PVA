<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\CompanyAccount;
use App\Contract;
use App\Employee;
use App\EmployeePayroll;
use App\EmployerNumber;
use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\ManagementEntity;
use App\Month;
use App\Payroll;
use App\PositionGroup;
use App\Procedure;
use App\TotalPayrollEmployee;
use App\TotalPayrollEmployer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;

class PayrollPrintController extends Controller {
	private function getFormattedData($year, $month, $valid_contracts, $with_account, $management_entity, $position_group, $employer_number) {
		$procedure = Procedure::where('month_id', $month)->where('year', $year)->select()->first();

		if (isset($procedure->id)) {
			$employees = array();
			$total_discounts = new TotalPayrollEmployee();
			$total_contributions = new TotalPayrollEmployer();
			$company = Company::select()->first();

			$payrolls = Payroll::where('procedure_id', $procedure->id)->orderBy('id')->get();
			// if (config('app.debug')) {
			//     $payrolls = Payroll::where('procedure_id',$procedure->id)->take(10)->orderBy('contract_id', 'ASC')->orderBy('id', 'ASC')->get();
			// }
			foreach ($payrolls as $key => $payroll) {
				$contract = $payroll->contract;
				$employee = $contract->employee;

				$rehired = true;
				$employee_contracts = $payroll->contract->employee->contracts;

				$e = new EmployeePayroll($payroll);

				if (count($employee_contracts) > 1) {
					$rehired = Util::valid_contract($payroll, $employee->last_contract());

					if ($rehired) {
						$e->setValidContact(true);
					}
				}

				if (($valid_contracts && !$e->valid_contract) || (($management_entity != 0) && ($e->management_entity_id != $management_entity)) || (($position_group != 0) && ($e->position_group_id != $position_group)) || ($employer_number && ($e->employer_number_id != $employer_number)) || ($with_account && !$employee->account_number)) {
					$e->setZeroAccounts();
				} else {
					$employees[] = $e;
				}

				$total_discounts->add_base_wage($e->base_wage);
				$total_discounts->add_quotable($e->quotable);
				$total_discounts->add_discount_old($e->discount_old);
				$total_discounts->add_discount_common_risk($e->discount_common_risk);
				$total_discounts->add_discount_commission($e->discount_commission);
				$total_discounts->add_discount_solidary($e->discount_solidary);
				$total_discounts->add_discount_national($e->discount_national);
				$total_discounts->add_total_amount_discount_law($e->total_amount_discount_law);
				$total_discounts->add_net_salary($e->net_salary);
				$total_discounts->add_discount_rc_iva($e->discount_rc_iva);
				$total_discounts->add_total_faults($e->discount_faults);
				$total_discounts->add_total_discounts($e->total_discounts);
				$total_discounts->add_payable_liquid($e->payable_liquid);

				$total_contributions->add_quotable($e->quotable);
				$total_contributions->add_contribution_insurance_company($e->contribution_insurance_company);
				$total_contributions->add_contribution_professional_risk($e->contribution_professional_risk);
				$total_contributions->add_contribution_employer_solidary($e->contribution_employer_solidary);
				$total_contributions->add_contribution_employer_housing($e->contribution_employer_housing);
				$total_contributions->add_total_contributions($e->total_contributions);
			}
		} else {
			abort(404);
		}

		return (object) array(
			"data" => [
				'total_discounts' => $total_discounts,
				'total_contributions' => $total_contributions,
				'employees' => $employees,
				'procedure' => $procedure,
				'tribute' => $procedure->employer_tribute,
				'company' => $company,
				'title' => (object) array(
					'year' => $year,
				),
			],
		);
	}

	/**
	 * Print PDF payroll reports.
	 *
	 * @param  integer  $year
	 * @param  integer  $month
	 * @param  string  $report_type
	 * @param  string  $report_name
	 * @param  boolean $valid_contracts
	 * @param  integer  $management_entity_id
	 * @param  integer  $position_group_id
	 * @param  integer  $employer_number_id
	 * @return \PDF
	 */
	public function print_pdf(Request $params, $year, $month) {
		$month = Month::where('id', $month)->select()->first();
		if (!$month) {
			abort(404);
		}

		$params = $params->all();

		$employer_number = 0;
		$position_group = 0;
		$management_entity = 0;
		$with_account = 0;
		$valid_contracts = 0;
		$report_name = '';
		$report_type = 'H';

		switch (count($params)) {
		case 7:
			$employer_number = request('employer_number');
		case 6:
			$position_group = request('position_group');
		case 5:
			$management_entity = request('management_entity');
		case 4:
			$with_account = request('with_account');
		case 3:
			$valid_contracts = request('valid_contracts');
		case 2:
			$report_name = request('report_name');
		case 1:
			$report_type = strtoupper(request('report_type'));
			break;
		default:
			abort(404);
		}

		$response = $this->getFormattedData($year, $month->id, $valid_contracts, $with_account, $management_entity, $position_group, $employer_number);

		$response->data['title']->subtitle = '';
		$response->data['title']->management_entity = '';
		$response->data['title']->position_group = '';
		$response->data['title']->employer_number = '';
		$response->data['title']->report_name = $report_name;
		$response->data['title']->report_type = $report_type;
		$response->data['title']->month = $month->name;

		switch ($report_type) {
		case 'H':
			$response->data['title']->name = 'PLANILLA DE HABERES';
			$response->data['title']->table_header = 'DESCUENTOS DEL SISTEMA DE PENSIONES';
			break;
		case 'P':
			$response->data['title']->name = 'PLANILLA PATRONAL';
			$response->data['title']->table_header = 'APORTES PATRONALES';
			break;
		case 'T':
			$response->data['title']->name = 'PLANILLA TRIBUTARIA';
			$response->data['title']->table_header = 'S.M.N.';
			$response->data['title']->table_header2 = $response->data['tribute']->minimun_salary;
			$response->data['title']->table_header3 = 'Saldo a favor de:';
			$response->data['title']->table_header4 = 'Saldo anterior a favor del dependiente';
			$response->data['title']->minimun_salary = $response->data['tribute']->minimun_salary;
			$response->data['title']->ufv = $response->data['tribute']->ufv;
			break;
		default:
			abort(404);
		}

		if ($management_entity) {
			$response->data['title']->management_entity = ManagementEntity::find($management_entity)->name;
		}
		if ($position_group) {
			$position_group = PositionGroup::find($position_group);
			$response->data['title']->position_group = $position_group->name;
			$response->data['company']->employer_number = $position_group->employer_number->number;
		}
		if ($employer_number) {
			$employer_number = EmployerNumber::find($employer_number);
			$response->data['title']->employer_number = $employer_number->number;
			$response->data['company']->employer_number = $employer_number->number;
		}

		// return response()->json($response);

		$file_name = implode(" ", [$response->data['title']->name, $report_name, $year, strtoupper($month->name)]) . ".pdf";

		// return view('payroll.print', $response->data);

		return \PDF::loadView('payroll.print', $response->data)
			->setOption('page-width', '216')
			->setOption('page-height', '330')
			->setOption('margin-left', '26')
			->setOption('margin-right', '0')
			->setOrientation('landscape')
			->setOption('encoding', 'utf-8')
			->setOption('footer-font-size', 5)
			->setOption('footer-center', '[page] de [topage] - Impreso el ' . date('m/d/Y H:i'))
			->stream($file_name);
	}

	/**
	 * Print TXT payroll reports.
	 *
	 * @param  integer  $year
	 * @param  integer  $month
	 * @return \TXT
	 */
	public function print_txt($year, $month) {
		$month = Month::where('id', $month)->select()->first();
		if (!$month) {
			abort(404);
		}

		$response = $this->getFormattedData($year, $month->id, 1, 0, 1, 0, 0, 0);
		$total_employees = count($response->data['employees']);

		if ($total_employees == 0) {
			abort(403);
		}

		$content = "";

		$content .= "sueldo del mes de " . strtolower($month->name) . " " . $year . " " . Util::fillZerosLeft(strval($total_employees), 4) . Carbon::now()->format('dmY') . "\r\n";

		$content .= CompanyAccount::where('active', true)->first()->account . Util::fillZerosLeft(strval(Util::format_number($response->data['total_discounts']->payable_liquid, 2, '', '.')), 12) . "\r\n";

		foreach ($response->data['employees'] as $i => $employee) {
			$content .= $employee->account_number . Util::fillZerosLeft(strval(Util::format_number($employee->payable_liquid, 2, '', '.')), 12) . "1";

			if ($i < ($total_employees - 1)) {
				$content .= "\r\n";
			}
		}

		$filename = implode('_', ["sueldos", strtolower($month->name), $year]) . ".txt";

		$headers = ['Content-type' => 'text/plain', 'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)];

		// return response()->json($content);

		return Response::make($content, 200, $headers);

	}

	public function print_ovt($year, $month) {
		$month = Month::where('id', $month)->select()->first();
		if (!$month) {
			abort(404);
		}

		$employees = $this->getFormattedData($year, $month->id, 0, 0, 0, 0, 0, 0)->data['employees'];
		$total_employees = count($employees);

		// return response()->json($employees);

		$content = "";

		$content .= implode(',', ["Nro", "Tipo de documento de identidad", "Número de documento de identidad", "Lugar de expedición", "Fecha de nacimiento", "Apellido Paterno", "Apellido Materno", "Nombres", "País de nacionalidad", "Sexo", "Jubilado", "¿Aporta a la AFP?", "¿Persona con discapacidad?", "Tutor de persona con discapacidad", "Fecha de ingreso", "Fecha de retiro", "Motivo retiro", "Caja de salud", "AFP a la que aporta", "NUA/CUA", "Sucursal o ubicación adicional", "Clasificación laboral", "Cargo", "Modalidad de contrato", "Tipo contrato", "Días pagados", "Horas pagadas", "Haber Básico", "Bono de antigüedad", "Horas extra", "Monto horas extra", "Horas recargo nocturno", "Monto horas extra nocturnas", "Horas extra dominicales", "Monto horas extra dominicales", "Domingos trabajados", "Monto domingo trabajado", "Nro. dominicales", "Salario dominical", "Bono producción", "Subsidio frontera", "Otros bonos y pagos", "RC-IVA", "Aporte Caja Salud", "Aporte AFP", "Otros descuentos", "\r\n"]);

		foreach ($employees as $i => $e) {
			$content .= implode(',', [++$i, "CI", $e->ci, $e->id_ext, $e->birth_date, $e->last_name, $e->mothers_last_name, $e->first_name, "BOLIVIA", $e->gender, "0", "1", "0", "0", $e->start_date, "", "", $e->ovt->insurance_company_id, $e->ovt->management_entity_id, $e->nua_cua, "1", "", mb_strtoupper(str_replace(",", " ", $e->position)), $e->ovt->contract_mode, $e->ovt->contract_type, $e->worked_days, "8", round($e->quotable, 2), "0", "", "", "", "", "", "", "", "", "", "", "", "", "", "", round($e->discount_old, 2), round($e->total_amount_discount_law, 2), round($e->discount_faults, 2)]);

			if ($i < ($total_employees)) {
				$content .= "\r\n";
			}
		}

		$filename = implode('_', ["planilla", "ovt", strtolower($month->name), $year]) . ".csv";

		$headers = ['Content-type' => 'text/plain', 'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)];

		// return response()->json($content);

		return Response::make($content, 200, $headers);
	}
}
