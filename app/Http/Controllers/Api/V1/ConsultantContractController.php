<?php

namespace App\Http\Controllers\Api\V1;

use App\ConsultantContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsultantContractController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return ConsultantContract::with('job_schedules', 'employee', 'employee.city_identity_card', 'consultant_position', 'consultant_position.charge', 'consultant_position.position_group', 'retirement_reason')
      ->orderBy('end_date', 'ASC')
      ->get();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $contract = ConsultantContract::create($request->all());
    foreach ($request->schedules as $schedule) {
      $contract->job_schedules()->attach($schedule);
    }
    return $contract;
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\ConsultantContract  $consultantContract
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    return ConsultantContract::findOrFail($id);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\ConsultantContract  $consultantContract
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $contract = ConsultantContract::findOrFail($id);
    $contract->fill($request->all());
    $contract->save();
    if ($request->schedule['id']) {
      $contract->job_schedules()->detach();
      $contract->job_schedules()->attach($request->schedule['id']);
      if ($request->schedule['id'] == 1) {
        $contract->job_schedules()->attach(2);
      }
    }
    return $contract;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\ConsultantContract  $consultantContract
   * @return \Illuminate\Http\Response
   */
  public function destroy(ConsultantContract $consultantContract)
  {
    $contract = ConsultantContract::findOrFail($id);
    $contract->delete();
    return $contract;
  }
}
