<?php
namespace App\Http\Controllers\Api\V1;

use App\EmployeePayroll;
use App\Http\Controllers\Controller;
use App\Payroll;
use App\Procedure;

class TicketController extends Controller {
	function print($id) {
		$procedure = Procedure::findOrFail($id);
		$grouped_payrolls = Payroll::where('procedure_id', $procedure->id)->leftJoin('employees as e', 'e.id', '=', 'payrolls.employee_id')->leftJoin('contracts as c', 'c.id', '=', 'payrolls.contract_id')->orderBy('e.last_name')->orderBy('e.mothers_last_name')->orderBy('c.start_date')->get()->groupBy('code');
		$payrolls = [];
		foreach ($grouped_payrolls as $payroll_group) {
			foreach ($payroll_group as $key => $pr) {
				if ($key == 0) {
					$p = new EmployeePayroll($pr);
				} else {
					$p->mergePayroll(new EmployeePayroll($pr));
				}
			}
			$p = $p->generateImage();
			$payrolls[] = $p;
		}

		$file_name = "Boletas de Pago de " . $procedure->month->name . " de " . $procedure->year . ".pdf";
		$data = [
			'payrolls' => $payrolls,
			'procedure' => $procedure,
		];
		return \PDF::loadView('tickets.print', $data)
			->setOption('page-width', '216')
			->setOption('page-height', '356')
			->setOption('margin-top', 0)
			->setOption('margin-bottom', 0)
			->setOption('margin-left', 4)
			->setOption('margin-right', 4)
			->setOption('encoding', 'utf-8')
			->stream($file_name);
	}
}
