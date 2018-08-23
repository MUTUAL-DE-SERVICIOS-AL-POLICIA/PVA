<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollForm;
use App\Payroll;
use App\Procedure;
use Illuminate\Http\Request;

/** @resource Payroll
 *
 * Resource to retrieve, store and update Payrolls data
 */
class PayrollController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return Payroll::get();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(PayrollForm $request) {
		$procedure = Procedure::findOrFail($request['procedure_id']);
		$year_shortened = substr(strval($procedure->year), -2);
		if (Payroll::where('code', 'LIKE', "%-$year_shortened")->count() == 0) {
			$code = implode([str_pad(1, 3, '0', STR_PAD_LEFT), $year_shortened], '-');
		} else {
			$payroll = Payroll::where('procedure_id', $request['procedure_id'])->where('charge_id', $request['charge_id'])->where('position_group_id', $request['position_group_id'])->where('position_id', $request['position_id'])->first();
			if ($payroll) {
				$code = $payroll->code;
			} else {
				$last_code = Payroll::where('procedure_id', $procedure->id)->orderBy('code', 'DESC')->select('code')->first();
				if ($last_code) {
					$last_code = intval(substr($last_code->code, 0, 3));
				} else {
					$last_code = Payroll::orderBy('code', 'DESC')->select('code')->first();
					$last_code = intval(substr($last_code->code, 0, 3));
				}
				$code = implode([str_pad($last_code + 1, 3, '0', STR_PAD_LEFT), $year_shortened], '-');
			}
		}
		return Payroll::create($request->all() + ['code' => $code]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return Payroll::findOrFail($id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(PayrollForm $request, $id) {
		$payroll = Payroll::findOrFail($id);
		$payroll->fill($request->all());
		$payroll->save();
		return $payroll;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$payroll = Payroll::findOrFail($id);
		$payroll->delete();
		return $payroll;
	}
}
