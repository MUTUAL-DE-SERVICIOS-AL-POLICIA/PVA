<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Month;

/** @resource Month
 *
 * Resource to retrieve and show Month data
 */

class MonthController extends Controller
{
	/**
	 * Display a listing of the months.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return Month::get();
	}

	/**
	 * Display the specified month.
	 *
	 * @param  \App\Month  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return Month::findOrFail($id);
	}

	/**
	 * Display the specified month with order.
	 *
	 * @param  \App\Month  $order
	 * @return \Illuminate\Http\Response
	 */
	public function order($order)
	{
		$month = Month::where('order', $order)->first();
		if ($month) {
			return $month;
		} else {
			abort(404);
		}
	}
}
