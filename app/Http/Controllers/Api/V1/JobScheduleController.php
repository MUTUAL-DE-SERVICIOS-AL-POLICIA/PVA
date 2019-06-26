<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobScheduleForm;
use App\JobSchedule;
use Illuminate\Http\Request;

/** @resource JobSchedule
 *
 * Resource to retrieve, store and update job schedules data
 */
class JobScheduleController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $schedules = JobSchedule::orderBy('id')->get();
    foreach ($schedules as $schedule) {
      $this->iso_format($request, $schedule);
    }
    return $schedules;
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(JobScheduleForm $request)
  {
    $schedule = JobSchedule::create($request->all());
    $this->iso_format($request, $schedule);
    return $schedule;
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request, $id)
  {
    $schedule = JobSchedule::findOrFail($id);
    $this->iso_format($request, $schedule);
    return $schedule;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(JobScheduleForm $request, $id)
  {
    $schedule = JobSchedule::findOrFail($id);
    $schedule->fill($request->all());
    $schedule->save();
    if ($request->has('iso_format')) {
      if ($request->input('iso_format') == true) {
        $schedule = JobSchedule::findOrFail($id);
        $schedule->iso_format();
      }
    }
    return $schedule;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $id)
  {
    $schedule = JobSchedule::findOrFail($id);
    $sch = $schedule;
    $schedule->delete();
    if ($request->has('iso_format')) {
      if (json_decode($request->input('iso_format')) === true) {
        $sch->iso_format();
        return $sch;
      }
    }
    return $schedule;
  }

  private function iso_format($request, $schedule)
  {
    if ($request->has('iso_format')) {
      if (json_decode($request->input('iso_format')) === true) {
        $schedule->iso_format();
      }
    }
  }
}
