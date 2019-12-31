<?php

namespace App\Http\Controllers\Api\V1;

use App\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
		return City::orderBy('name')->get();
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

    public function update(Request $request, $id) {
		$city = City::findOrFail($id);
		$city->fill($request->all());
		$city->save();
		return $city;
	}
}
