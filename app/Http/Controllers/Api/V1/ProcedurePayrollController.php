<?php

namespace App\Http\Controllers\Api\V1;

use App\Contract;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollForm;
use App\Payroll;
use App\Procedure;

/** @resource ProcedurePayroll
 *
 * Resource to retrieve and store payrolls with procedure data
 */

class ProcedurePayrollController extends Controller
{

	/**
	 * Display a listing of the payrolls .
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function get_payrolls($procedure_id)
	{
		$procedure = Procedure::findOrFail($procedure_id);
		return Payroll::where('procedure_id', $procedure->id)->with('contract.position', 'contract.position.charge', 'contract.position.position_group', 'contract.employee', 'contract.employee.city_identity_card')->leftjoin('contracts as c', 'c.id', '=', 'payrolls.contract_id')->leftjoin('employees as e', 'e.id', '=', 'c.employee_id')->orderBy('e.last_name')->orderBy('e.mothers_last_name')->select('payrolls.*')->get();
	}

	/**
	 * Stores all contract payrolls if non exists payrolls related to the procedure ID.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function generate_payrolls($procedure_id)
	{
		$procedure = Procedure::findOrFail($procedure_id);
		if (Payroll::where('procedure_id', $procedure->id)->count() == 0) {
			$contracts = new Contract();
			$contracts = $contracts->valid_date($procedure->year, $procedure->month->order);
			$p = new PayrollController();
			$payroll = new PayrollForm();
			foreach ($contracts as $key => $contract) {
				$payroll['procedure_id'] = $procedure->id;
				$payroll['contract_id'] = $contract->id;
				$payroll['employee_id'] = $contract->employee->id;
				$payroll['position_id'] = $contract->position->id;
				$payroll['charge_id'] = $contract->position->charge->id;
				$payroll['position_group_id'] = $contract->position->position_group->id;
				$p->store($payroll);
			}
			return response()->json([
				'generated' => Payroll::where('procedure_id', $procedure->id)->count(),
			]);
		} else {
			abort(403);
		}
	}

	public function delete_payrolls($procedure_id)
	{
		$procedure = Procedure::findOrFail($procedure_id);
		$payrolls = Payroll::where('procedure_id', $procedure->id)->pluck('id')->toArray();
		$deleted_payrolls = Payroll::destroy($payrolls);
		$procedure->delete();
		return response()->json([
			'procedure' => $procedure,
			'deleted' => $deleted_payrolls
		], 200);
	}
}
