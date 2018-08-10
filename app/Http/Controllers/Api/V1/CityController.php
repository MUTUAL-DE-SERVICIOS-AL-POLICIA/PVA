<?php

namespace App\Http\Controllers;

use App\City;

/** @resource City
 *
 * Resource to retrieve and show City data
 */

class CityController extends Controller {
	/**
	 * Display a listing of the cities.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return City::all();
	}

	/**
	 * Display the specified city.
	 *
	 * @param  \App\City  $city
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return City::findOrFail($id);
	}
}
