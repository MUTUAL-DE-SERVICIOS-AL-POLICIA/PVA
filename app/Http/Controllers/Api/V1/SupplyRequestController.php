<?php

namespace App\Http\Controllers\Api\V1;

use App\SupplyRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplyRequestController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
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
   * @param  \App\SupplyRequest  $supplyRequest
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $request = SupplyRequest::find($id);
    $request->subarticles;
    return $request;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\SupplyRequest  $supplyRequest
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\SupplyRequest  $supplyRequest
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
