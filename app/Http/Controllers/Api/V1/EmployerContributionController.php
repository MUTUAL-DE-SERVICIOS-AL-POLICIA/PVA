<?php

namespace App\Http\Controllers\Api\V1;

use App\EmployerContribution;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployerContributionForm;
use Illuminate\Http\Request;

/** @resource EmployerContribution
 *
 * Resource to retrieve, store and destroy EmployerContribution data
 */

class EmployerContributionController extends Controller {
	/**
	 * Display a listing of the employer contributions.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return EmployerContribution::get();
	}

	/**
	 * Store a newly created employer contribution in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(EmployerContributionForm $request) {
		$employer_contribution = new EmployerContribution($request->all());
		EmployerContribution::where('active', true)->update([
			'active' => false,
		]);
		$employer_contribution->active = true;
		$employer_contribution->save();
		return $employer_contribution;
	}

	/**
	 * Display the specified employer contribution contribution.
	 *
	 * @param  \App\EmployerContribution  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return EmployerContribution::findOrFail($id);
	}

	/**
	 * Remove the specified employer contribution from storage.
	 *
	 * @param  \App\EmployerContribution  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$company_account = EmployerContribution::findOrFail($id);
		$company_account->delete();
		return $company_account;
	}
}
