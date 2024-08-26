<?php

namespace App\Http\Controllers\Api\V1;

use App\ContractType;
use App\Http\Controllers\Controller;

/** @resource ContractType
 *
 * Resource to retrieve and show ContractType data
 */

class ContractTypeController extends Controller {
	/**
	 * Display a listing of the contract types.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return ContractType::get();
	}

	/**
	 * Display the specified contract type.
	 *
	 * @param  \App\ContractType  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return ContractType::findOrFail($id);
	}
}
