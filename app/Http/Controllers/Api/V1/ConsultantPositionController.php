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
    $position = ConsultantPosition::where('name', $request['name'])->where('charge_id', $request['charge_id'])->where('position_group_id', $request['position_group_id'])->first();
    if (!$position) {
      $position = ConsultantPosition::create($request->all());
    }
    return $position;
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\ConsultantPosition  $consultantPosition
   * @return \Illuminate\Http\Response
   */
  public function show(ConsultantPositionForm $request, $id)
  {
    $position = ConsultantPosition::findOrFail($id);
    if ($position->charge_id == $request['charge_id'] && $position->position_group_id == $request['position_group_id']) {
      return $position;
    }
    abort(404);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\ConsultantPosition  $consultantPosition
   * @return \Illuminate\Http\Response
   */
  public function update(ConsultantPositionForm $request, $id)
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

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\ConsultantPosition  $consultantPosition
   * @return \Illuminate\Http\Response
   */
  public function find(Request $request)
  {
    $position = ConsultantPosition::where('name', $request['name'])->where('charge_id', $request['charge_id'])->where('position_group_id', $request['position_group_id'])->first();
    return $position;
  }

}
