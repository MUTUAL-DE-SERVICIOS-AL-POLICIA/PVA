<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\InsuranceCompany;

/** @resource InsuranceCompany
 *
 * Resource to retrieve and show InsuranceCompany data
 */

class InsuranceCompanyController extends Controller {
	/**
	 * Display a listing of the insurance companies' data.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return InsuranceCompany::get();
	}

	/**
	 * Display the specified insurance company.
	 *
	 * @param  \App\InsuranceCompany  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return InsuranceCompany::findOrFail($id);
	}
}
