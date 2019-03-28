<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Procedure;

/** @resource ProcedureYear
 *
 * Resource to retrieve procedures data by year
 */

class ProcedureYearController extends Controller {
	/**
	 * Display a listing of the procedures' years.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function years() {
		return Procedure::select('year')->distinct()->orderBy('year', 'DESC')->pluck('year')->toArray();
	}

	/**
	 * Display a listing of the procedures with the selected year.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function with_year($year) {
		return Procedure::where('year', $year)->leftjoin('months', 'months.id', '=', 'procedures.month_id')->orderBy('months.order', 'DESC')->select('procedures.*', 'months.name as month_name', 'months.order as month_order', 'months.order as month_shortened')->whereNull('deleted_at')->get();
	}
}
