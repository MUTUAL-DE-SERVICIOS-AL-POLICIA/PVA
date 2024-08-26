<?php

namespace App\Http\Controllers\Api\V1;

use App\EmployerNumber;
use App\Http\Controllers\Controller;
use App\InsuranceCompany;

/** @resource EmployerNumberInsuranceCompany
 *
 * Resource to show and update EmployerNumber-InsuranceCompany relation
 */

class EmployerNumberInsuranceCompanyController extends Controller {
	/**
	 * Display the specified insurance companies related to employer number.
	 *
	 * @param  \App\EmployerNumber  $employer_number_id
	 * @param  \App\InsuranceCompany  $insurance_company_id
	 * @return \Illuminate\Http\Response
	 */
	public function get_insurance_company($employer_number_id, $insurance_company_id) {
		$employer_number = EmployerNumber::findOrFail($employer_number_id);
		if ($employer_number->insurance_company_id == $insurance_company_id) {
			return InsuranceCompany::findOrFail($insurance_company_id);
		} else {
			return App::abort(404);
		}
	}

	/**
	 * Attach insurance company to a employer number.
	 *
	 * @param  \App\EmployerNumber  $employer_number_id
	 * @param  \App\InsuranceCompany  $insurance_company_id
	 * @return \Illuminate\Http\Response
	 */
	public function set_insurance_company($employer_number_id, $insurance_company_id) {
		$employer_number = EmployerNumber::findOrFail($employer_number_id);
		$employer_number->insurance_company()->associate(InsuranceCompany::findOrFail($insurance_company_id))->save();
		$employer_number->insurance_company;
		return $employer_number;
	}
}
