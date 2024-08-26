<?php

namespace App\Http\Controllers\Api\V1;

use App\ConsultantContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConsultantPayrollForm;
use App\Http\Requests\ConsultantPayrollProcedureForm;
use App\ConsultantPayroll;
use App\ConsultantProcedure;

/** @resource ProcedurePayroll
 *
 * Resource to retrieve and store payrolls with procedure data
 */

class ConsultantProcedurePayrollController extends Controller
{

	/**
	 * Display a listing of the payrolls .
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function get_payrolls($consultant_procedure_id)
	{
		$procedure = ConsultantProcedure::findOrFail($consultant_procedure_id);
		return ConsultantPayroll::where('consultant_procedure_id', $procedure->id)->with('consultant_contract.consultant_position', 'consultant_contract.consultant_position.charge', 'consultant_contract.consultant_position.position_group', 'consultant_contract.employee', 'consultant_contract.employee.city_identity_card')->leftjoin('consultant_contracts as c', 'c.id', '=', 'consultant_payrolls.consultant_contract_id')->leftjoin('employees as e', 'e.id', '=', 'c.employee_id')->orderBy('e.last_name')->orderBy('e.mothers_last_name')->select('consultant_payrolls.*')->get();
	}

	/**
	 * Stores all contract payrolls if non exists payrolls related to the procedure ID.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function generate_payrolls($consultant_procedure_id)
	{
		$procedure = ConsultantProcedure::findOrFail($consultant_procedure_id);
		if (ConsultantPayroll::where('consultant_procedure_id', $procedure->id)->count() == 0) {
			$contracts = new ConsultantContract();
			$contracts = $contracts->valid_date($procedure->year, $procedure->month->order);
			$p = new ConsultantPayrollController();
			$payroll = new ConsultantPayrollForm();
			foreach ($contracts as $key => $contract) {
				$payroll['consultant_procedure_id'] = $procedure->id;
				$payroll['consultant_contract_id'] = $contract->id;
				$payroll['employee_id'] = $contract->employee->id;
				$payroll['consultant_position_id'] = $contract->consultant_position->id;
				$payroll['charge_id'] = $contract->consultant_position->charge->id;
				$payroll['position_group_id'] = $contract->consultant_position->position_group->id;
				$p->store($payroll);
			}
			return response()->json([
				'generated' => ConsultantPayroll::where('consultant_procedure_id', $procedure->id)->count(),
			]);
		} else {
			abort(403);
		}
	}

	public function delete_payrolls($consultant_procedure_id)
	{
		$procedure = ConsultantProcedure::findOrFail($consultant_procedure_id);
		$payrolls = ConsultantPayroll::where('consultant_procedure_id', $procedure->id)->pluck('id')->toArray();
		$deleted_payrolls = ConsultantPayroll::destroy($payrolls);
		$procedure->delete();
		return response()->json([
			'procedure' => $procedure,
			'deleted' => $deleted_payrolls
		], 200);
	}

	/**
	 * Get specific payroll if exists in the procedure
	 *
	 * @param  int  $consultant_procedure_id
	 * @param  int  $consultant_contract_id
	 * @param  int  $employee_id
	 * @param  int  $charge_id
	 * @param  int  $position_group_id
	 * @param  int  $consultant_position_id
	 * @return \Illuminate\Http\Response
	 */
	public function getPayrollProcedure($consultant_procedure_id, ConsultantPayrollProcedureForm $request)
	{
		$payroll = ConsultantPayroll::where('consultant_procedure_id', $consultant_procedure_id)->where('consultant_contract_id', $request['consultant_contract_id'])->where('employee_id', $request['employee_id'])->where('charge_id', $request['charge_id'])->where('position_group_id', $request['position_group_id'])->where('consultant_position_id', $request['consultant_position_id'])->first();
		if ($payroll) {
			return $payroll;
		} else {
			abort(404);
		}
	}
}
