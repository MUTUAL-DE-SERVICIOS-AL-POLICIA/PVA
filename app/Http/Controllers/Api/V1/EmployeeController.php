<?php

namespace App\Http\Controllers\Api\V1;

use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeForm;
use Illuminate\Http\Request;

/** @resource Employee
 *
 * Resource to retrieve, store and update Emmployee data
 */

class EmployeeController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return Employee::with('city_identity_card')
			->with('insurance_company')
			->with('management_entity')
			->with('city_birth')
			->orderBy('last_name')
			->get();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(EmployeeForm $request) {
		$employee = Employee::create($request->all());
		return $employee;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return Employee::findOrFail($id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(EmployeeForm $request, $id) {
		$employee = Employee::findOrFail($id);
		$employee->fill($request->all());
		$employee->save();
		return $employee;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$employee = Employee::findOrFail($id);
		$employee->delete();
		return $employee;
	}
}
