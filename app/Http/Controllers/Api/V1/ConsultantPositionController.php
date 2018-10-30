<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConsultantPositionForm;
use App\ConsultantPosition;
use Illuminate\Http\Request;

class ConsultantPositionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return ConsultantPosition::with('charge', 'position_group')->get();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ConsultantPositionForm $request)
  {
    $position = ConsultantPosition::create($request->all());
    return $position;
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\ConsultantPosition  $consultantPosition
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    return ConsultantPosition::findOrFail($id);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\ConsultantPosition  $consultantPosition
   * @return \Illuminate\Http\Response
   */
  public function update(PositionForm $request, $id)
  {
    $position = ConsultantPosition::findOrFail($id);
    $position->fill($request->all());
    $position->save();
    return $position;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\ConsultantPosition  $consultantPosition
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $position = ConsultantPosition::findOrFail($id);
    $position->delete();
    return $position;
  }
}
