<?php

namespace App\Http\Controllers\Api\V1;

use App\AttendanceUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AttendanceDevice;
use ZKLibrary;
use Carbon\Carbon;
use App\AttendanceCheck;
use Dotenv\Regex\Result;

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
                $message = 'Usuario con BADGENUMBER: ' . $key . ' inexistente';
                $errors[] = $message;
              }
            }
          } else {
            $message = 'El dispositivo ' . $device->MachineAlias . ' no tiene registros';
            $errors[] = $message;
          }
          $device->users = $users;
          break;
        } else {
          $i++;
          $device->users = [];
          $message = 'No se puede conectar con dispositivo ' . $device->MachineAlias;
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
              if ($user) {
                foreach ($user_check as $check) {
                  $c = new AttendanceCheck();
                  $c->USERID = intval($user->USERID);
                  $c->CHECKTIME = Carbon::parse($check[3])->format('Ymd H:i:s');
                  // $c->CHECKTYPE = 'I';
                  $c->VERIFYCODE = intval($check[2]);
                  $c->SENSORID = strval($device->MachineNumber);
                  $c->sn = $device->sn;
                  $exists = AttendanceCheck::where($c->toArray())->first();
                  if (!$exists) $c->save();
                }
                array_push($users, [
                  'user' => $user->NAME,
                  'checks' => count($user_check)
                ]);
              } else {
                $message = 'Usuario con BADGENUMBER: ' . $key . ' inexistente';
                $errors[] = $message;
              }
            }
          } else {
            $message = 'El dispositivo ' . $device->MachineAlias . ' no tiene registros';
            $errors[] = $message;
          }
          break;
        } else {
          $i++;
          \Log::error('Device ' . $device->MachineAlias . ' unreachable');
        }
      } while ($i < 3);
    }
    foreach ($errors as $error) {
      \Log::error($error);
    }
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
                'checks' => count($user_check)
              ]);
            } else {
              $message = 'Usuario con BADGENUMBER: ' . $key . ' inexistente';
              $errors[] = $message;
            }
          }
        } else {
          $message = 'El dispositivo ' . $device->MachineAlias . ' no tiene registros';
          $errors[] = $message;
        }
        break;
      } else {
        $i++;
        \Log::error('Device ' . $device->MachineAlias . ' unreachable');
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
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\AttendanceUser  $attendanceUser
   * @return \Illuminate\Http\Response
   */
  public function destroy($id = null)
  {
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
          \Log::error('Device ' . $device->MachineAlias . ' unreachable');
        }
      } while ($i < 3);
    }
    return response()->json([
      'message' => 'Eliminados registros de asistencia'
    ], 200);
  }
}
