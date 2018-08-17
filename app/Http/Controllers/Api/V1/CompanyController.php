<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyEditForm;
use App\Http\Requests\CompanyStoreForm;
use Illuminate\Http\Request;

/** @resource Company
 *
 * Resource to retrieve, store and edit Company data
 */

class CompanyController extends Controller {
	/**
	 * Display Company's data.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return Company::with('document','document.document_type')
						->get();
	}

	/**
	 * Store a newly Company if no one found in the database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CompanyStoreForm $request) {
		if (Company::count() == 0) {
			return Company::create($request->all());
		} else {
			abort(403);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return Company::findOrFail($id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(CompanyEditForm $request, $id) {
		$company = Company::findOrFail($id);
		$company->fill($request->all());
		$company->save();
		return $company;
	}
}
