<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\EmployerNumber;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployerNumberEditForm;
use App\Http\Requests\EmployerNumberStoreForm;
use Illuminate\Http\Request;

/** @resource EmployerNumber
 *
 * Resource to retrieve, show, store, update and destroy EmployerNumber data
 */

class EmployerNumberController extends Controller {
	public function __construct() {
		$this->company = Company::get()->first();
	}

	/**
	 * Display a listing of the employer numbers.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return EmployerNumber::get();
	}

	/**
	 * Store a newly created employer number in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(EmployerNumberStoreForm $request) {
		$employer_number = new EmployerNumber($request->all());
		$employer_number->company_id = $this->company->id;
		$employer_number->save();
		return $employer_number;
	}

	/**
	 * Display the specified employer number.
	 *
	 * @param  \App\EmployerNumber  $employerNumber
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return EmployerNumber::findOrFail($id);
	}

	/**
	 * Update the specified employer number in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\EmployerNumber  $employerNumber
	 * @return \Illuminate\Http\Response
	 */
	public function update(EmployerNumberEditForm $request, $id) {
		$employer_number = EmployerNumber::findOrFail($id);
		$employer_number->fill($request->all());
		$employer_number->save();
		return $employer_number;
	}

	/**
	 * Remove the specified employer number from storage.
	 *
	 * @param  \App\EmployerNumber  $employerNumber
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$employer_number = EmployerNumber::findOrFail($id);
		$employer_number->delete();
		return $employer_number;
	}
}
