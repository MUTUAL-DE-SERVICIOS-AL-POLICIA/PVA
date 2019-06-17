<?php

namespace App\Http\Controllers\Api\V1;

use App\Employee;
use App\Payroll;
use App\DepartureReason;
use App\AttendanceUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeEditForm;
use App\Http\Requests\EmployeeStoreForm;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use Util;

/** @resource Employee
 *
 * Resource to retrieve, store and update Emmployee data
 */

class EmployeeController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $employees = Employee::with('city_identity_card')
      ->with('management_entity')
      ->with('city_birth')
      ->orderBy('last_name')
      ->get();

    foreach ($employees as $employee) {
      $employee->total_contracts = $employee->total_contracts();
      $employee->consultant = $employee->consultant();

      if ($employee->consultant === null) {
        $employee->position = null;
      } elseif ($employee->consultant === true) {
        $employee->position = $employee->last_consultant_contract()->consultant_position->name;
      } elseif ($employee->consultant === false) {
        $employee->position = $employee->last_contract()->position->name;
      }
    }

    return $employees;
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(EmployeeStoreForm $request)
  {
    $employee = Employee::create($request->all());
    return $employee;
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request, $id)
  {
    $employee = Employee::findOrFail($id);

    if (!$request->has('departure_reason')) {
      $request['departure_reason'] = DepartureReason::whereName('CON GOCE DE HABERES')->first()->id;
    }

    $employee->remaining_departures = [
      'monthly' => $employee->monthly_remaining_departures(),
      'annually' => $employee->annually_remaining_departures($request['departure_reason'])
    ];

    return $employee;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(EmployeeEditForm $request, $id)
  {
    $employee = Employee::findOrFail($id);
    $employee->fill($request->all());
    $employee->save();
    return $employee;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    if (Payroll::where('employee_id', $id)->count() > 0) {
      return response()->json([
        'message' => 'No autorizado',
        'errors' => [
          'type' => ['El empleado está registrado en una o más planillas'],
        ],
      ], 403);
    } else {
      $employee = Employee::findOrFail($id);
      $employee->delete();
      return $employee;
    }
  }

  public function attendance(Request $request, $id)
  {
    $employee = Employee::findOrFail($id);
    $attendance_user = AttendanceUser::where('ssn', 'like', $employee->identity_card . '%')->orderBy('USERID', 'DESC')->first();
    if ($attendance_user) {
      if (!$request->has('month')) {
        $to = CarbonImmutable::now();
      } else {
        $to = CarbonImmutable::parse($request->input('month'))->day(19);
      }
      if ($to->day >= 20) {
        $from = $to->day(20);
      } else {
        $from = $to->subMonth()->day(20);
      }

      $checks = $attendance_user->checks()->where('checktime', '>=', $from->startOfDay()->format('Ymd H:i:s'))->where('checktime', '<=', $to->endOfDay()->format('Ymd H:i:s'))->get();

      switch ($employee->consultant()) {
        case true:
          $job_schedules = $employee->last_consultant_contract()->job_schedules;
          break;
        case false:
          $job_schedules = $employee->last_contract()->job_schedules;
          break;
        case null:
          $job_schedules = null;
          break;
      }

      foreach ($checks as $check) {
        $checktime = CarbonImmutable::parse($check->checktime);
        $check->date = $checktime->toDateString();
        $check->time = $checktime->format('H:i');
        $check->color = 'orange';
        if ($job_schedules) {
          $attendance = Util::attendance_checktype($job_schedules, $check->checktime, true);
          if ($attendance->delay->minutes > 0) {
            $check->color = 'red';
          } else {
            if ($attendance->type == 'I') {
              $check->color = 'green';
            } elseif ($attendance->type == 'O') {
              $check->color = 'blue';
            }
          }
        }
        unset($check->checktime);
      }

      return response()->json([
        'from' => $from->toDateString(),
        'to' => $to->toDateString(),
        'checks' => collect(array_unique($checks->all()))->values()
      ], 200);
    } else {
      return response()->json([
        'message' => 'Usuario inexistente',
        'errors' => [
          'type' => ['Usuario inexistente en la base de datos'],
        ],
      ], 404);
    }
  }
}
