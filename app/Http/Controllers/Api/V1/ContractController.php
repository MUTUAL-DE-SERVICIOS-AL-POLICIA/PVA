<?php

namespace App\Http\Controllers\Api\V1;

use App\Contract;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContractForm;
use Illuminate\Http\Request;

/** @resource Contract
 *
 * Resource to retrieve, store and update contracts data
 */
class ContractController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return Contract::with('employee', 'insurance_company', 'employee.city_identity_card', 'position', 'position.charge', 'position.position_group', 'contract_type', 'contract_mode', 'retirement_reason')->get();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(ContractForm $request) {
		$contract = Contract::create($request->all());
		return $contract;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return Contract::findOrFail($id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$contract = Contract::findOrFail($id);
		$contract->fill($request->all());
		$contract->save();
		return $contract;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$contract = Contract::findOrFail($id);
		$contract->delete();
		return $contract;
	}
}
