<?php

namespace App\Http\Controllers\Api\V1;

use App\AttendanceUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AttendanceDevice;
use ZKLibrary;
use Util;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\AttendanceCheck;
use App\Employee;

class AttendanceController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $errors = [];
    $devices = AttendanceDevice::select('MachineNumber', 'MachineAlias', 'IP', 'Port', 'sn')->where('Enabled', true)->get();
    if ($devices->count() == 0) {
      abort(404);
    }
    foreach ($devices as $device) {
      $users = [];
      $zk = new ZKLibrary($device->IP, $device->Port);
      $i = 0;
      do {
        if ($zk->connect()) {
          $checks = $zk->getAttendance();
          $zk->disconnect();
          if (count($checks) > 0) {
            $user_checks = collect($checks)->groupBy(1)->toArray();
            foreach ($user_checks as $key => $user_check) {
              $user = AttendanceUser::where('BADGENUMBER', strval($key))->first();
              if ($user) {
                array_push($users, [
                  'user' => $user->NAME,
                  'checks' => count($user_check)
                ]);
              } else {
                $message = 'Usuario con BADGENUMBER: ' . $key . ' inexistente en la base de datos';
                $errors[] = $message;
              }
            }
          } else {
            $message = 'El dispositivo ' . $device->MachineAlias . ' no contiene registros';
            $errors[] = $message;
          }
          $device->users = $users;
          break;
        } else {
          $i++;
          $device->users = [];
          $message = 'No se pudo conectar con el dispositivo ' . $device->MachineAlias;
          if (!in_array($message, $errors)) $errors[] = $message;
          \Log::error($message);
        }
      } while ($i < 3);
    }
    foreach ($errors as $error) {
      \Log::error($error);
    }
    return response()->json([
      'errors' => $errors,
      'devices' => $devices
    ], 200);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $errors = [];
    $users = [];
    $query = AttendanceDevice::select('MachineNumber', 'MachineAlias', 'IP', 'Port', 'sn')->where('Enabled', true);
    if ($request->has('id')) {
      $devices = $query->where('MachineNumber', $request->input('id'));
    }
    $devices = $query->get();
    if ($devices->count() == 0) {
      abort(404);
    }
    \Log::info('Starting Attendace synchronization.');
    foreach ($devices as $device) {
      $zk = new ZKLibrary($device->IP, $device->Port);
      $i = 0;
      do {
        if ($zk->connect()) {
          $checks = $zk->getAttendance();
        //   $zk->disconnect();
          \Log::info('Encontrados ' . count($checks) . ' registros para sincronizar...');
          if (count($checks) > 0) {
            $checks = collect($checks)->groupBy(1)->toArray();
            foreach ($checks as $key => $user_check) {
              $user = AttendanceUser::where('BADGENUMBER', intval(utf8_decode($key)))->first();
              $employee = Employee::whereIdentityCard(explode(' ', $user->SSN)[0])->first();
              if ($employee) {
                $employee_type = $employee->consultant();
                if ($employee_type === true) {
                  $job_schedules = $employee->last_consultant_contract()->job_schedules;
                } elseif ($employee_type === false) {
                  $job_schedules = $employee->last_contract()->job_schedules;
                } else {
                  $employee = null;
                }
              } else {
                $message = 'No se encontró ningún empleado con CI: ' . $user->SSN;
                if (!in_array($message, $errors)) $errors[] = $message;
              }
              if ($user) {
                $count = 0;
                foreach ($user_check as $check) {
                  $user_id = intval($user->USERID);
                  $checktime = Carbon::parse($check[3])->format('Ymd H:i:s');
                  $exists = AttendanceCheck::where('USERID', $user_id)->where('CHECKTIME', $checktime)->first();
                  if (!$exists) {
                    $c = new AttendanceCheck();
                    $c->USERID = $user_id;
                    $c->CHECKTIME = $checktime;
                    $c->VERIFYCODE = intval($check[2]);
                    $c->SENSORID = strval($device->MachineNumber);
                    $c->sn = $device->sn;
                    $count++;
                  } else {
                    $c = $exists;
                  }
                  $c->CHECKTIME = $checktime;
                  if ($employee) {
                    $checktype = Util::attendance_checktype($job_schedules, $check[3]);
                    $c->CHECKTYPE = $checktype->type;
                  } else {
                    $c->CHECKTYPE = 'X';
                  }
                  if (!$exists) {
                    $c->save();
                  } else {
                    AttendanceCheck::where('USERID', $user_id)->where('CHECKTIME', $checktime)->update($c->toArray());
                  }
                }
                if ($count > 0) {
                  array_push($users, [
                    'user' => $user->NAME,
                    'checks' => count($user_check),
                    'device' => $device->MachineNumber
                  ]);
                }
              } else {
                $message = 'Usuario con BADGENUMBER: ' . intval(utf8_decode($key)) . ' inexistente';
                $errors[] = $message;
              }
            }
          } else {
            $message = 'El dispositivo ' . $device->MachineAlias . ' no contiene registros';
            $errors[] = $message;
          }
          break;
        } else {
          $i++;
          $message = 'No se pudo conectar con el dispositivo ' . $device->MachineAlias;
          if (!in_array($message, $errors)) $errors[] = $message;
        }
      } while ($i < 3);
      \Log::info('Sincronización con dispositivo ' . $device->MachineAlias . ' terminada.');
    }
    \Log::info('Attendace synchronization has ended');
    return response()->json([
      'errors' => $errors,
      'added' => $users
    ], 200);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\AttendanceUser  $attendanceUser
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $errors = [];
    $device = AttendanceDevice::find($id);
    if (!$device) {
      abort(404);
    }
    $users = [];
    $zk = new ZKLibrary($device->IP, $device->Port);
    $i = 0;
    do {
      if ($zk->connect()) {
        $checks = $zk->getAttendance();
        $zk->disconnect();
        if (count($checks) > 0) {
          $user_checks = collect($checks)->groupBy(1)->toArray();
          foreach ($user_checks as $key => $user_check) {
            $user = AttendanceUser::where('BADGENUMBER', strval($key))->first();
            if ($user) {
              array_push($users, [
                'user' => $user->NAME,
                'checks' => count($user_check),
                'device' => $device->MachineNumber
              ]);
            } else {
              $message = 'Usuario con BADGENUMBER: ' . $key . ' inexistente';
              $errors[] = $message;
            }
          }
        } else {
          $message = 'El dispositivo ' . $device->MachineAlias . ' no contiene registros';
          $errors[] = $message;
        }
        break;
      } else {
        $i++;
        $device->users = [];
        $message = 'No se pudo conectar con el dispositivo ' . $device->MachineAlias;
        if (!in_array($message, $errors)) $errors[] = $message;
        \Log::error($message);
      }
    } while ($i < 3);
    foreach ($errors as $error) {
      \Log::error($error);
    }
    $device->users = $users;
    return response()->json([
      'errors' => $errors,
      'device' => $device
    ], 200);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\AttendanceUser  $attendanceUser
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $employee = Employee::findOrFail($id);
    $attendance_user = AttendanceUser::where('ssn', 'like', $employee->identity_card . '%')->orderBy('USERID', 'DESC')->first();
    $user_id = intval($attendance_user->USERID);

    if ($attendance_user) {
      $checktime = Carbon::parse($request->input('date') . ' ' . $request->input('time'))->format('Ymd H:i:s');
      $exists = AttendanceCheck::where('USERID', $user_id)->where('CHECKTIME', $checktime)->first();
      if ($exists) {
        return $exists;
      } else {
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
        if ($job_schedules) {
          $last_check = AttendanceCheck::where('USERID', $user_id)->orderBy('CHECKTIME', 'DESC')->first();
          if ($last_check) {
            $checktype = Util::attendance_checktype($job_schedules, $checktime);
            try {
              $c = new AttendanceCheck();
              $c->CHECKTYPE = $checktype->type;
              $c->CHECKTIME = $checktime;
              $c->USERID = $user_id;
              $c->VERIFYCODE = $last_check->VERIFYCODE;
              $c->SENSORID = $last_check->SENSORID;
              $c->sn = $last_check->sn;
              $c->save();
              return $c;
            } catch (\Exception $e) {
              abort(409);
            }
          }
        }
      }
    }
    abort(404);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\AttendanceUser  $attendanceUser
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $errors = [];
    $query = AttendanceDevice::select('MachineNumber', 'MachineAlias', 'IP', 'Port', 'sn')->where('Enabled', true);
    if ($id != 'all') {
      $devices = $query->where('MachineNumber', $id);
    }
    $devices = $query->get();
    if ($devices->count() == 0) {
      abort(404);
    }
    \Log::info('Erasing Attendace devices.');
    foreach ($devices as $device) {
      $zk = new ZKLibrary($device->IP, $device->Port);
      $i = 0;
      do {
        if ($zk->connect()) {
          $zk->disableDevice();
          $zk->clearAttendance();
          $zk->enableDevice();
          $zk->disconnect();
          \Log::info('Device ' . $device->MachineAlias . ' erased.');
          break;
        } else {
          $i++;
          $message = 'No se pudo conectar con el dispositivo ' . $device->MachineAlias;
          if (!in_array($message, $errors)) $errors[] = $message;
          \Log::error($message);
        }
      } while ($i < 3);
    }
    return response()->json([
      'errors' => $errors,
      'message' => 'Eliminados registros de asistencia'
    ], 200);
  }

  public function print(Request $request, $type)
  {
    $employees = Employee::whereActive(true)->orderBy('last_name')->select('id')->get();

    if (!$request->has('from') && !$request->has('to')) {
      $to = CarbonImmutable::now();

      if ($type == 'consultant') {
        $from = $to->day(1);
        $to = $from->endOfMonth();
      } else {
        if ($to->day == 20) {
          $from = $to;
        } elseif ($to->day < 20) {
          $from = $to->subMonth()->day(20);
          $to = $to->day(19);
        } else {
          $from = $to->day(20);
          $to = $from->addMonth()->day(19);
        }
      }

      $req = new Request([
        'from' => $from->toDateString(),
        'to' => $to->toDateString(),
        'with_discounts' => true,
        'type' => 'json'
      ]);
    } else {
      $req = new Request([
        'from' => $request->input('from'),
        'to' => $request->input('to'),
        'with_discounts' => true,
        'type' => 'json'
      ]);
    }

    $data = [];

    foreach ($employees as $key => $employee) {
      $consultant = $employee->consultant();
      if ($consultant === true && $type == 'eventual' || $consultant === false && $type == 'consultant' || $consultant === null) {
        unset($employees[$key]);
        continue;
      }

      $response = (new EmployeeController)->attendance($req, $employee->id);
      if ($response->status() == 200) {
        $response = json_decode($response->getContent());
        $data[] = [
          'from' => $response->from,
          'to' => $response->to,
          'employee' => $response->employee,
          'checks' => (array) $response->checks,
        ];
      }
    }

    $date = Carbon::parse($req->input('to'));
    $file_name = implode(" ", ['marcados', 'de', $date->ISOFormat('MMMM'), 'de', $date->year]) . ".pdf";

    $options = [
      'orientation' => 'landscape',
      'page-width' => '216',
      'page-height' => '279',
      'margin-top' => '8',
      'margin-bottom' => '4',
      'margin-left' => '5',
      'margin-right' => '5',
      'encoding' => 'UTF-8',
      'user-style-sheet' => public_path('css/report-print.min.css')
    ];

    $pdf = \PDF::loadView('attendance.print', [
      'employees' => $data
    ]);
    $pdf->setOptions($options);

    return $pdf->stream($file_name);
  }
}
