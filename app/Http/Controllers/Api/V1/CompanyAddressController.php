<?php

namespace App\Http\Controllers;

use App\CompanyAddress;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyAddressEditForm;
use App\Http\Requests\CompanyAddressStoreForm;
use Illuminate\Http\Request;

/** @resource CompanyAddress
 *
 * Resource to retrieve, show, store, update and destroy CompanyAddress data
 */

class CompanyAddressController extends Controller {
	/**
	 * Display a listing of the compamy addresses' data.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return CompanyAddress::get();
	}

	/**
	 * Store a newly created compamy address in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CompanyAddressStoreForm $request) {
		$company_address = new CompanyAddress($request->all());
		$company_address->save();
		return $company_address;
	}

	/**
	 * Display the specified compamy address.
	 *
	 * @param  \App\CompanyAddress  $companyAddress
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return CompanyAddress::findOrFail($id);
	}

	/**
	 * Update the specified compamy address in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\CompanyAddress  $companyAddress
	 * @return \Illuminate\Http\Response
	 */
	public function update(CompanyAddressEditForm $request, $id) {
		$company_address = CompanyAddress::findOrFail($id);
		$company_address->fill($request->all());
		$company_address->save();
		return $company_address;
	}

	/**
	 * Remove the specified compamy address from storage.
	 *
	 * @param  \App\CompanyAddress  $companyAddress
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$company_address = CompanyAddress::findOrFail($id);
		$company_address->delete();
		return $company_address;
	}
}
