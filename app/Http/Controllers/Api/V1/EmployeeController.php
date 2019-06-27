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
use Carbon;
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
        $to = CarbonImmutable::parse($request->input('month'));
      }

      if (json_decode($request->input('with_discounts'))) {
        $with_discounts = true;
      } else {
        $with_discounts = false;
      }

      if ($to->day == 20) {
        $from = $to;
      } elseif ($to->day < 20) {
        $from = $to->subMonth()->day(20);
        $to = $to->day(19);
      } else {
        $from = $to->day(20);
        $to = $from->addMonth()->day(19);
      }

      $checks = $attendance_user->checks()->where('checktime', '>=', $from->startOfDay()->format('Ymd H:i:s'))->where('checktime', '<=', $to->endOfDay()->format('Ymd H:i:s'))->get();

      if ($checks->count() == 0) {
        return response()->json([
          'message' => 'Sin registros de asistencia',
          'errors' => [
            'type' => ['Sin registros de asistencia para el rango de fechas'],
          ],
        ], 404);
      }

      $employee->full_name = $employee->fullName('uppercase', 'last_name_first');
      $employee->consultant = $employee->consultant();
      switch ($employee->consultant) {
        case true:
          $employee->contract = $employee->last_consultant_contract();
          $employee->position_name = $employee->contract->consultant_position->name;
          $job_schedules = $employee->contract->job_schedules;
          break;
        case false:
          $employee->contract = $employee->last_contract();
          $employee->position_name = $employee->contract->position->name;
          $job_schedules = $employee->contract->job_schedules;
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
          $attendance = Util::attendance_checktype($job_schedules, $check->checktime, $with_discounts);
          if ($with_discounts) {
            $check->delay = $attendance->delay;
          }
          $check->shift = $attendance->shift;
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

      $data = [
        'from' => $from->toDateString(),
        'to' => $to->toDateString(),
        'checks' => $with_discounts ? Util::filter_checks($checks) : collect(array_unique($checks->all()))->values()
      ];

      if ($with_discounts) {
        $data['employee'] = $employee;
      }

      if ($request->input('type') == 'pdf') {
        $file_name = implode(" ", ['marcados', $data['employee']->fullName('lowercase', 'last_name_first'), 'del', $data['from'], 'al', $data['to']]) . ".pdf";

        $options = [
          'orientation' => 'landscape',
          'page-width' => '216',
          'page-height' => '279',
          'margin-top' => '4',
          'margin-bottom' => '4',
          'margin-left' => '5',
          'margin-right' => '5',
          'encoding' => 'UTF-8',
          'user-style-sheet' => public_path('css/report-print.min.css')
        ];

        $pdf = \PDF::loadView('attendance.print', [
          'employees' => [$data]
        ]);
        $pdf->setOptions($options);

        return $pdf->stream($file_name);
      }

      return response()->json($data, 200);
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
