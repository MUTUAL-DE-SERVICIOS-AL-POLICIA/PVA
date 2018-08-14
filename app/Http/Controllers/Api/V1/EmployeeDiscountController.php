<?php

namespace App\Http\Controllers\Api\V1;

use App\EmployeeDiscount;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeDiscountForm;
use Illuminate\Http\Request;

/** @resource EmployeeDiscount
 *
 * Resource to retrieve, store and destroy EmployeeDiscount data
 */

class EmployeeDiscountController extends Controller {
	/**
	 * Display a listing of the employee discounts.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return EmployeeDiscount::get();
	}

	/**
	 * Store a newly created employee discount in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(EmployeeDiscountForm $request) {
		$employee_discount = new EmployeeDiscount($request->all());
		EmployeeDiscount::where('active', true)->update([
			'active' => false,
		]);
		$employee_discount->active = true;
		$employee_discount->save();
		return $employee_discount;
	}

	/**
	 * Display the specified employee discount.
	 *
	 * @param  \App\EmployeeDiscount  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return EmployeeDiscount::findOrFail($id);
	}

	/**
	 * Enable the specified employee discount from storage.
	 *
	 * @param  \App\EmployeeDiscount  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update($id) {
		$employee_discount = EmployeeDiscount::findOrFail($id);
		EmployeeDiscount::where('active', true)->update([
			'active' => false,
		]);
		$employee_discount->active = true;
		$employee_discount->save();
		return $employee_discount;
	}

	/**
	 * Disable the specified employee discount from storage.
	 *
	 * @param  \App\EmployeeDiscount  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$employee_discount = EmployeeDiscount::findOrFail($id);
		$employee_discount->active = false;
		$employee_discount->save();
		return $employee_discount;
	}
}
