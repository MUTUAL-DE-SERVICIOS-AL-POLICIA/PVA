<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DaysOnVacation;

class DaysOnVacationController extends Controller
{
  /**
	 * Display a listing of the Cas certification.
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function index()
    {
      return DaysOnVacation::get();
    }

    /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
    public function store(Request $request)
    {
      $days_on_vacation = DaysOnVacation::create($request->all());
      return $days_on_vacation;
    }

    /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
    public function show($id)
    {
      DaysOnVacation::findOrFail($id);
    }

    /**
   * Update a resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
    public function update($id)
    {
      $days_on_vacation = DaysOnVacation::findOrFail($id);
      $days_on_vacation->fill($days_on_vacation->all());
      $days_on_vacation->save();
    }

    public function destroy($id)
    {
      $days_on_vacation = DaysOnVacation::findOrFail($id);
      $days_on_vacation->delete();
      return $days_on_vacation;
    }
}
