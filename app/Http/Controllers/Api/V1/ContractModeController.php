<?php

namespace App\Http\Controllers;

use App\ContractMode;

/** @resource ContractMode
 *
 * Resource to retrieve and show ContractMode data
 */

class ContractModeController extends Controller {
	/**
	 * Display a listing of the contract modes.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return ContractMode::get();
	}

	/**
	 * Display the specified contract mode.
	 *
	 * @param  \App\ContractMode  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return ContractMode::findOrFail($id);
	}
}
