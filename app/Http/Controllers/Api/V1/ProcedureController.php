<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\EmployeeDiscount;
use App\EmployerContribution;
use App\EmployerTribute;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcedureForm;
use App\Procedure;
use Illuminate\Http\Request;

/** @resource Procedure
 *
 * Resource to retrieve, store and update procedures data
 */

class ProcedureController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return Procedure::with('month')->get();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(ProcedureForm $request)
	{
		if (Procedure::where('active', true)->count() == 0) {
			if (Procedure::where('year', $request['year'])->where('month_id', $request['month_id'])->count() == 0) {
				$discount = EmployeeDiscount::where('active', true)->first();
				$contribution = EmployerContribution::where('active', true)->first();
				$tribute = EmployerTribute::orderBy('id', 'desc')->first();
				$procedure = new Procedure();
				$procedure->year = $request['year'];
				$procedure->month_id = $request['month_id'];
				$procedure->employee_discount_id = $discount->id;
				$procedure->employer_contribution_id = $contribution->id;
				$procedure->employer_tribute_id = $tribute->id;
				$procedure->active = true;
				$procedure->save();
				return $procedure;
			} else {
				return response()->json([
					'message' => 'No autorizado',
					'errors' => [
						'type' => ['Ya existe el registro de ese mes'],
					],
				], 400);
			}
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
	public function show($id)
	{
		return Procedure::with('month')->findOrFail($id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$procedure = Procedure::findOrFail($id);
		$procedure->fill($request->all());
		$procedure->save();
		$procedure->month;
		return $procedure;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$procedure = Procedure::findOrFail($id);
		$procedure->delete();
		return $procedure;
	}

	/**
	 * Display the specified procedure's discounts.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function discounts($id)
	{
		return Procedure::where('id', $id)->with('month')->with('employee_discount')->with('employer_contribution')->first();
	}

	/**
	 * Display the date of procedure.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function date($id)
	{
		$procedure = Procedure::findOrFail($id);
		return response()->json([
			'first_date' => Carbon::create(2018, 8)->startOfMonth()->format('Y-m-d'),
			'end_date' => Carbon::create(2018, 8)->endOfMonth()->format('Y-m-d'),
			'now' => Carbon::now()->format('Y-m-d'),
		]);
	}

	/**
	 * Display a last procedures stored in DB.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function order($order)
	{
		return Procedure::leftjoin('months as m', 'm.id', '=', 'month_id')->orderBy('year', ($order == 'last') ? 'DESC' : 'ASC')->orderBy('m.order', ($order == 'last') ? 'DESC' : 'ASC')->select('procedures.*')->first();
	}
}
