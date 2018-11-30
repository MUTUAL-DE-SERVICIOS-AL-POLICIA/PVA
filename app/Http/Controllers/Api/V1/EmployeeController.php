<?php

namespace App\Http\Controllers\Api\V1;

use App\Employee;
use App\Payroll;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeEditForm;
use App\Http\Requests\EmployeeStoreForm;
use Illuminate\Http\Request;

/** @resource Employee
 *
 * Resource to retrieve, store and update Emmployee data
 */

class EmployeeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$employees = Employee::with('city_identity_card')
			->with('management_entity')
			->with('city_birth')
			->orderBy('last_name')
			->get();

		foreach ($employees as $employee) {
			$employee->consultant = $employee->consultant();

			if ($employee->consultant === null) {
				$employee->position = null;
			} elseif ($employee->consultant === true) {
				$employee->position = $employee->last_consultant_contract()->consultant_position->name;
			} elseif ($employee->consultant === false) {
				$employee->position = $employee->last_contract()->position->name;
			}
		}

		return $employees;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(EmployeeStoreForm $request)
	{
		$employee = Employee::create($request->all());
		return $employee;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return Employee::findOrFail($id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(EmployeeEditForm $request, $id)
	{
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
	public function destroy($id)
	{
		if (Payroll::where('employee_id', $id)->count() > 0) {
			return response()->json([
				'message' => 'No autorizado',
				'errors' => [
					'type' => ['El empleado está registrado en una o más planillas'],
				],
			], 403);
		} else {
			$employee = Employee::findOrFail($id);
			$employee->delete();
			return $employee;
		}
	}
}
