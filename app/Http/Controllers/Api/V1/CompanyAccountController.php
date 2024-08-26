<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\CompanyAccount;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyAccountEditForm;
use App\Http\Requests\CompanyAccountStoreForm;
use Illuminate\Http\Request;

/** @resource CompanyAccount
 *
 * Resource to retrieve, show, store, update and destroy CompanyAccount data
 */

class CompanyAccountController extends Controller {
	public function __construct() {
		$this->company = Company::get()->first();
	}

	/**
	 * Display a listing of the company accounts data.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return CompanyAccount::get();
	}

	/**
	 * Store a newly created company account in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CompanyAccountStoreForm $request) {
		$company_account = new CompanyAccount($request->all());
		$company_account->company_id = $this->company->id;
		$company_account->save();
		return $company_account;
	}

	/**
	 * Display the specified company account.
	 *
	 * @param  \App\CompanyAccount  $companyAccount
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return CompanyAccount::findOrFail($id);
	}

	/**
	 * Update the specified company account in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\CompanyAccount  $companyAccount
	 * @return \Illuminate\Http\Response
	 */
	public function update(CompanyAccountEditForm $request, $id) {
		$company_account = CompanyAccount::findOrFail($id);
		$company_account->fill($request->all());
		$company_account->save();
		return $company_account;
	}

	/**
	 * Remove the specified company account from storage.
	 *
	 * @param  \App\CompanyAccount  $companyAccount
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$company_account = CompanyAccount::findOrFail($id);
		$company_account->delete();
		return $company_account;
	}
}
