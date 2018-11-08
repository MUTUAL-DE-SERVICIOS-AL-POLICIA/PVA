<?php

namespace App\Http\Controllers\Api\V1;

use App\ConsultantContract;
use App\ConsultantProcedure;
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
    if ($request->schedules) {
      $contract->job_schedules()->detach();
      foreach ($request->schedules as $schedule) {
        $contract->job_schedules()->attach($schedule);
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
  public function destroy($id)
  {
    $contract = ConsultantContract::findOrFail($id);
    $contract->delete();
    return $contract;
  }

  /**
   * Display the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function positionFree($id)
  {
    $contract = ConsultantContract::where('active', true)->where('consultant_position_id', $id)->first();
    if ($contract) {
      return response()->json([
        'free' => false
      ]);
    } else {
      return response()->json([
        'free' => true
      ]);
    }
  }

  /**
   * Display the contract's list with valid date from procedure.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function valid_date($procedure_id)
  {
    $procedure = ConsultantProcedure::findOrFail($procedure_id);
    $contracts = new ConsultantContract();
    $contracts = $contracts->valid_date($procedure->year, $procedure->month->order);
    foreach ($contracts as $contract) {
      $contract->employee;
      $contract->employee->city_identity_card;
      $contract->consultant_position->position_group;
      $contract->consultant_position->charge;
    }
    return $contracts;
  }
}
