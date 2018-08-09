<?php

namespace App\Http\Controllers;

use App\City;

class CityController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return City::all();
	}
}
