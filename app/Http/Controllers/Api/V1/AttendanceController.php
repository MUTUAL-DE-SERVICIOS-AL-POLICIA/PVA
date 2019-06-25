<?php

namespace App\Http\Controllers\Api\V1;

use App\AttendanceUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AttendanceDevice;
use ZKLibrary;
use Util;
use Carbon\Carbon;
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
    \Log::info('Starting Attendace synchronization');
    $errors = [];
    $users = [];
    $query = AttendanceDevice::select('MachineNumber', 'MachineAlias', 'IP', 'Port', 'sn')->where('Enabled', true);
    if ($request->has('id')) {
      $devices = $query->where('MachineNumber', $request->input('id'))->get();
    } else {
      $devices = $query->get();
    }
    if ($devices->count() == 0) {
      abort(404);
    }
    foreach ($devices as $device) {
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
              $employee = Employee::whereIdentityCard(explode(' ', $user->SSN)[0])->first();
              if ($employee) {
                switch ($employee->consultant()) {
                  case true:
                    $job_schedules = $employee->last_consultant_contract()->job_schedules;
                    break;
                  case false:
                    $job_schedules = $employee->last_contract()->job_schedules;
                    break;
                  case null:
                    $employee = null;
                    break;
                }
              } else {
                $message = 'No se encontró ningún empleado con CI.: ' . $user->SSN;
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
                  if ($employee) {
                    $checktype = Util::attendance_checktype($job_schedules, $check[3]);
                    $c->CHECKTYPE = $checktype->type;
                  } else {
                    $c->CHECKTYPE = 'X';
                  }
                  try {
                    $c->save();
                  } catch (\Exception $error) {
                    try {
                      $c->CHECKTIME = $checktime;
                      if ($exists) {
                        if ($exists->CHECKTYPE != $c->CHECKTYPE) AttendanceCheck::where('USERID', $user_id)->where('CHECKTIME', $checktime)->update($c->toArray());
                      }
                    } catch (\Exception $e) {
                      \Log::error('Cannot save check where USERID=' . $user_id . ' and CHECKTIME=' . $checktime);
                      \Log::error($e->getMessage());
                    }
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
          $message = 'No se pudo conectar con el dispositivo ' . $device->MachineAlias;
          if (!in_array($message, $errors)) $errors[] = $message;
          \Log::error($message);
        }
      } while ($i < 3);
    }
    foreach ($errors as $error) {
      \Log::error($error);
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
  public function destroy($id = null)
  {
    $errors = [];
    $query = AttendanceDevice::select('MachineNumber', 'MachineAlias', 'IP', 'Port', 'sn')->where('Enabled', true);
    if (!$id) {
      $devices = $query->get();
    } else {
      $devices = $query->where('MachineNumber', $id)->get();
    }
    if ($devices->count() == 0) {
      abort(404);
    }
    foreach ($devices as $device) {
      $zk = new ZKLibrary($device->IP, $device->Port);
      $i = 0;
      do {
        if ($zk->connect()) {
          $zk->clearAttendance();
          $zk->disconnect();
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
}
