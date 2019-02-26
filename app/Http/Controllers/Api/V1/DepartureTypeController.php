<?php

namespace App\Http\Controllers\Api\V1;

use App\DepartureType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** @resource DepartureType
 *
 * Resource to retrieve, store and update departure types data
 */
class DepartureTypeController extends Controller
{
  /**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
  public function index(Request $request)
  {
    $with_reasons = true;
    if ($request->exists('with_reasons')) $with_reasons = json_decode($request->input('with_reasons'));
    if ($with_reasons) return DepartureType::with('departure_reasons')->get();
    return DepartureType::get();
  }

  /**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
  public function store(Request $request)
  { }

  /**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
  public function show($id)
  {
    return DepartureType::findOrFail($id);
  }

  /**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
  public function update(Request $request, $id)
  { }

  /**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
  public function destroy($id)
  { }
}
