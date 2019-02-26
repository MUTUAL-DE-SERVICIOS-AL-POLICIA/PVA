<?php

namespace App\Http\Controllers\Api\V1;

use App\DepartureGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartureGroupController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return DepartureGroup::get();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\DepartureGroup  $departureGroup
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $group = DepartureGroup::findOrFail($id);
    $group->departure_reasons;
    return $group;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\DepartureGroup  $departureGroup
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\DepartureGroup  $departureGroup
   * @return \Illuminate\Http\Response
   */
  public function destroy(DepartureGroup $departureGroup)
  {
    //
  }
}
