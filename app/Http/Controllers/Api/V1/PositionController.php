<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PositionForm;
use App\Position;
use Illuminate\Http\Request;

/** @resource Position
 *
 * Resource to retrieve, store and update position data
 */
class PositionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return Position::with('charge', 'position_group')->get();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(PositionForm $request)
  {
    $position = Position::create($request->all());
    return $position;
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    return Position::findOrFail($id);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(PositionForm $request, $id)
  {
    $position = Position::findOrFail($id);
    $position->fill($request->all());
    $position->save();
    return $position;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $position = Position::findOrFail($id);
    $position->delete();
    return $position;
  }
}
