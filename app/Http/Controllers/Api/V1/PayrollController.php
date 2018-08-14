<?php

namespace App\Http\Controllers\Api\V1;

use App\Payroll;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollForm;
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
		$payroll = Payroll::create($request->all());
		return $payroll;
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
