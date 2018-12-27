<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\CompanyAccount;
use App\ConsultantContract;
use App\Employee;
use App\EmployeeConsultantPayroll;
use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\ManagementEntity;
use App\Month;
use App\ConsultantPayroll;
use App\PositionGroup;
use App\ConsultantProcedure;
use App\TotalConsultantPayrollEmployee;
use App\Certificate;
use App\DocumentType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use Maatwebsite\Excel\Facades\Excel;

class ConsultantPayrollPrintController extends Controller
{
	private function getFormattedData($year, $month, $valid_contracts, $with_account, $position_group)
	{
		$procedure = ConsultantProcedure::where('month_id', $month)->where('year', $year)->select()->first();

		if (isset($procedure->id)) {
			$employees = array();
			$total_discounts = new TotalConsultantPayrollEmployee();
			$company = Company::select()->first();

			$payrolls = ConsultantPayroll::where('consultant_procedure_id', $procedure->id)->leftjoin('consultant_contracts as c', 'c.id', '=', 'consultant_payrolls.consultant_contract_id')->leftjoin('employees as e', 'e.id', '=', 'c.employee_id')->orderBy('e.last_name')->orderBy('e.mothers_last_name')->orderBy('c.start_date')->select('consultant_payrolls.*')->get();

			foreach ($payrolls as $key => $payroll) {
				$contract = $payroll->consultant_contract;
				$employee = $contract->employee;

				$rehired = true;
				$employee_contracts = $employee->consultant_contracts;

				$e = new EmployeeConsultantPayroll($payroll);

				if (count($employee_contracts) > 1) {
					$rehired = Util::valid_contract($payroll, $employee->last_consultant_contract(), 'consultant');

					if ($rehired) {
						$e->setValidContact(true);
					}
				}

				if (($valid_contracts && !$e->valid_contract) || (($position_group != 0) && ($e->position_group_id != $position_group)) || ($with_account && !$employee->account_number)) {
					$e->setZeroAccounts();
				} else {
					$employees[] = $e;
				}

				$total_discounts->add_base_wage($e->base_wage);
				$total_discounts->add_net_salary($e->net_salary);
				$total_discounts->add_total_faults($e->discount_faults);
				$total_discounts->add_payable_liquid($e->payable_liquid);
			}
		} else {
			abort(404);
		}

		return (object)array(
			"data" => [
				'total_discounts' => $total_discounts,
				'employees' => $employees,
				'procedure' => $procedure,
				'company' => $company,
				'title' => (object)array(
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
	 * @return \PDF
	 */
	public function print_pdf(Request $params, $year, $month)
	{
		$month = Month::where('id', $month)->select()->first();
		if (!$month) {
			abort(404);
		}

		$params = $params->all();

		$position_group = 0;
		$with_account = 0;
		$valid_contracts = 0;
		$report_name = '';
		$report_type = 'H';

		switch (count($params)) {
			case 4:
				$position_group = request('position_group');
			case 3:
				$with_account = request('with_account');
			case 2:
				$valid_contracts = request('valid_contracts');
			case 1:
				$report_name = request('report_name');
				break;
			default:
				abort(404);
		}

		$response = $this->getFormattedData($year, $month->id, $valid_contracts, $with_account, $position_group);

		$response->data['title']->subtitle = '';
		$response->data['title']->position_group = '';
		$response->data['title']->report_name = $report_name;
		$response->data['title']->report_type = $report_type;
		$response->data['title']->month = $month->name;

		$response->data['title']->name = 'PLANILLA DE HABERES CONSULTORES EN LÍNEA';

		if ($position_group) {
			$position_group = PositionGroup::find($position_group);
			$response->data['title']->position_group = $position_group->name;
		}

		$file_name = implode(" ", [$response->data['title']->name, $report_name, $year, strtoupper($month->name)]) . ".pdf";

		$footerHtml = view()->make('partials.footer')->with(array('paginator' => true))->render();

		$options = [
			'orientation' => 'landscape',
			'page-width' => '216',
			'page-height' => '330',
			'margin-left' => '26',
			'margin-right' => '0',
			'encoding' => 'UTF-8',
			'footer-html' => $footerHtml,
			'user-style-sheet' => public_path('css/payroll-print.min.css')
		];

		$pdf = \PDF::loadView('payroll.consultant', $response->data);
		$pdf->setOptions($options);

		return $pdf->stream($file_name);
	}

	/**
	 * Print TXT payroll reports.
	 *
	 * @param  integer  $year
	 * @param  integer  $month
	 * @return \TXT
	 */
	public function print_txt($year, $month)
	{
		$month = Month::findorFail($month);

		$response = $this->getFormattedData($year, $month->order, 1, 1, 0);
		$total_employees = count($response->data['employees']);

		if ($total_employees == 0) {
			abort(404);
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

		$headers = [
			'Content-type' => 'text/plain',
			'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)
		];

		return Response::make($content, 200, $headers);
	}

	public function certificate($employee_id)
	{
		$employees = array();
		$total_discounts = new TotalConsultantPayrollEmployee();
		$company = Company::select()->first();
		$payrolls = ConsultantPayroll::where('employee_id', $employee_id)
			->join('consultant_procedures as p', 'p.id', '=', 'consultant_payrolls.procedure_id')
			->join('months as m', 'm.id', '=', 'p.month_id')
			->orderBy('p.year')
			->orderBy('m.order')
			->get();
		foreach ($payrolls as $key => $payroll) {
			$contract = $payroll->consultant_contract;
			$employee = $contract->employee;

			$rehired = true;
			$employee_contracts = $employee->consultant_contracts;

			$e = new EmployeeConsultantPayroll($payroll);

			if (count($employee_contracts) > 1) {
				$rehired = Util::valid_contract($payroll, $employee->last_contract(), 'consultant');

				if ($rehired) {
					$e->setValidContact(true);
				}
			}
			$employees[] = $e;
		}
		return $employees;
	}

	public function print_certificate($employee_id)
	{
		$data['payrolls'] = $this->certificate($employee_id);
		$data['contract'] = Contract::with('employee', 'employee.city_identity_card', 'consultant_position')->where('employee_id', $employee_id)->orderBy('end_date', 'desc')->select('consultant_contracts.active as act', '*')->first();

		$correlative = 1;
		$year = date('Y');
		$document_type = DocumentType::where('shortened', 'C.T.')->first();
		$certificate = Certificate::where('document_type_id', $document_type->id)->orderBy('correlative', 'desc')->first();
		if ($certificate) {
			$correlative = $certificate->correlative + 1;
			if ($certificate->year != $year) {
				$correlative = 1;
			}
		}

		$new_certificate = new Certificate;
		$new_certificate->document_type_id = $document_type->id;
		$new_certificate->correlative = $correlative;
		$new_certificate->year = $year;
		$new_certificate->data = json_encode($data);
		$new_certificate->save();

		$data['certificate'] = $new_certificate;

		return \PDF::loadView('payroll.print_certificate', $data)
			->setOption('page-width', '220')
			->setOption('page-height', '280')
			->setOption('margin-left', '20')
			->setOption('margin-right', '15')
			->setOption('encoding', 'utf-8')
			->stream('certificado');
	}
}
