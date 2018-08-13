<?php

namespace App\Http\Controllers;

use App\RetirementReason;

/** @resource RetirementReason
 *
 * Resource to retrieve and show RetirementReason data
 */

class RetirementReasonController extends Controller {
	/**
	 * Display a listing of the retirement reasons.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return RetirementReason::get();
	}

	/**
	 * Display the specified retirement reason.
	 *
	 * @param  \App\RetirementReason  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return RetirementReason::findOrFail($id);
	}
}
