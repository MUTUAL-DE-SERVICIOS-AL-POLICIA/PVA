<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vacation;

class VacationController extends Controller
{
        /**
	 * Display a listing of range of vacations.
	 *
	 * @return \Illuminate\Http\Response
	 */
  public function index()
  {
    return Vacation::orderBy('id','asc')->get();
  }

  /**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
  public function store(Request $request)
  {
    $request['active'] = true;
    $vacation = Vacation::create($request->all());
    return $vacation;
  }

  /**
 * Display the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
  public function show($id)
  {
    return Vacation::findOrFail($id);
  }

  /**
 * Update a resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
  public function update(Request $request, $id)
  {
    $vacation = Vacation::findOrFail($id);
    $vacation->fill($request->all());
    return $vacation->save();
  }

  public function destroy($id)
  {
    $vacation = Vacation::findOrFail($id);
    $vacation->delete();
    return $vacation;
  }
}
