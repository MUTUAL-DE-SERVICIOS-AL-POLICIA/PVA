<?php

namespace App\Http\Controllers\Api\V1;

use App\EmployeeDiscount;
use App\EmployerContribution;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcedureForm;
use App\Procedure;
use Carbon;
use Illuminate\Http\Request;

/** @resource Procedure
 *
 * Resource to retrieve, store and update procedures data
 */

class ProcedureController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return Procedure::get();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(ProcedureForm $request) {
		if (Procedure::where('active', true)->count() == 0) {
			$discount = EmployeeDiscount::where('active', true)->first();
			$contribution = EmployerContribution::where('active', true)->first();
			$procedure = new Procedure();
			$procedure->year = $request['year'];
			$procedure->month_id = $request['month_id'];
			$procedure->employee_discount_id = $discount->id;
			$procedure->employer_contribution_id = $contribution->id;
			$procedure->save();
			return $procedure;
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
		return Procedure::findOrFail($id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$procedure = Procedure::findOrFail($id);
		$procedure->fill($request->all());
		$procedure->save();
		if ($procedure->active == false) {
			$newDate = Carbon::create($procedure->year, $procedure->month_id, 1)->addMonth(1);
			$this->store(new ProcedureForm([
				'year' => $newDate->year,
				'month_id' => $newDate->month,
			]));
		}
		return $procedure;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$procedure = Procedure::findOrFail($id);
		$procedure->delete();
		return $procedure;
	}
}
