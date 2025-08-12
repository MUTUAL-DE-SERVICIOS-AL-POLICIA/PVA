<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Employee;
use App\User;
use Ldap;
use Carbon;
use Illuminate\Support\Facades\DB;

class LdapController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $ldap = new Ldap();
    return response()->json($ldap->list_entries());
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return \Illuminate\Http\Response
   */
  public function store()
  {
    $ldap = new Ldap();
    $ldap->create_group();
    $employees = Employee::where('active', true)->pluck('id')->toArray();

    $search = $ldap->list_entries();
    $entries = [];

    foreach ($search as $e) {
      $entries[] = $e->employeeNumber;
    }

    $added = array_diff($employees, $entries);
    $success_added = 0;
    foreach ($added as $key => $e) {
      $new_entry = Employee::findOrFail($e);
      $last_contract = $new_entry->last_contract();
      $last_consultant_contract = $new_entry->last_consultant_contract();
      if ($last_contract || $last_consultant_contract) {
        $givenName = $new_entry->first_name;
        if ($new_entry->second_name) $givenName .= ' ' . $new_entry->second_name;
        $sn = $new_entry->last_name;
        if ($new_entry->mothers_last_name) $sn .= ' ' . $new_entry->mothers_last_name;
        if ($last_contract) {
          if ($ldap->create_entry([
            'sn' => $sn,
            'givenName' => $givenName,
            'title' => $new_entry->last_contract()->position->name,
            'employeeNumber' => $new_entry->id
          ])) {
            $success_added++;
          } else {
            $added[$key] = $new_entry;
          }
        } elseif ($last_consultant_contract) {
          if ($ldap->create_entry([
            'sn' => $sn,
            'givenName' => $givenName,
            'title' => $new_entry->last_consultant_contract()->consultant_position->name,
            'employeeNumber' => $new_entry->id
          ])) {
            $success_added++;
          } else {
            $added[$key] = $new_entry;
          }
        }
      } else {
        $added[$key] = $new_entry;
        //$new_entry->active = false;
        //$new_entry->save();
      }
    }

    $removed = array_diff($entries, $employees);
    $success_removed = 0;
    foreach ($removed as $key => $e) {
      if ($ldap->delete_entry($e)) {
        $success_removed++;
      } else {
        $removed[$key] = $e;
      }
    }

    $ldap = new Ldap();
    $search = $ldap->list_entries();
    $success_updated = 0;
    foreach ($search as $ldap_employee) {
      $employee = Employee::find($ldap_employee->employeeNumber);
      $last_contract = $employee->last_contract();
      $last_consultant_contract = $employee->last_consultant_contract();
      if ($last_contract && $last_contract->active) {
        $position = $last_contract->position->name;
      } elseif ($last_consultant_contract && $last_consultant_contract->active) {
        $position = $last_consultant_contract->consultant_position->name;
      }
      if ($ldap_employee->title != $position) {
        $ldap->modify_entry($employee->id, [
          'title' => $position
        ]);
        $success_updated++;
      }
    }

    $response = [
      'employees' => (object)[
        'total' => count($employees),
        'new' => $added
      ],
      'entries' => (object)[
        'total' => count($entries),
        'old' => $removed
      ],
      'diff' => (object)[
        'added' => $success_added,
        'removed' => $success_removed,
        'updated' => $success_updated
      ]
    ];

    if (env('ZAMMAD_SYNC')) {
      $zammad = $this->zammad_sync();
      if (array_key_exists('result', $zammad)) {
        $response['zammad'] = $zammad['result'];
      } elseif (array_key_exists('error', $zammad)) {
        $response['zammad']['error'] = $zammad['error'];
      } else {
        $response['zammad'] = $zammad;
      }
    }

    //llamado a la sincronizacion con el sistema de correspondencia
    //$response['correspondence'] = $this->sync_correspondence();
    return response()->json($response);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $ldap = new Ldap();
    return response()->json($ldap->get_entry($id));
  }

  /**
   * Update the specified resource in storage.
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update($id)
  {
    $ldap = new Ldap();
    $old_entry = $ldap->get_entry($id);

    $employee = Employee::findOrFail($id);
    $last_contract = $employee->last_contract();

    $user = User::where('username', $old_entry['uid'])->where('active', true)->first();

    if ($user) {
      $user->position = $employee->last_contract()->position->name;
      $user->save();
    }

    return response()->json([
      'employee' => $employee,
      'updated' => $ldap->modify_entry($id, [
        'sn' => implode(' ', [$employee->last_name, $employee->mothers_last_name]),
        'givenName' => implode(' ', [$employee->first_name, $employee->second_name]),
        'title' => $employee->last_contract()->position->name
      ])
    ]);
  }

  /**
   * Restart user password.
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    Employee::findOrFail($id);
    $ldap = new Ldap();
    $user = $ldap->get_entry($id);
    if ($user) {
      if ($ldap->update_password($user['uid'], $user['uid'])) {
        $ldap->unbind();
        return response()->json($user, 200);
      }
    }
    $ldap->unbind();
    return response()->json([
      'message' => 'Bad Request Error',
      'errors' => [
        'type' => ['Usuario inexistente en el servidor LDAP'],
      ],
    ], 400);
  }

  private function zammad_sync()
  {
    $route = 'api/v1/integration/ldap/job_start';
    $max_request = 10000;

    $headers = [
      'Content-Type:application/json',
      'Authorization: Basic ' . base64_encode(implode(':', [env('ZAMMAD_USER'), env('ZAMMAD_PASSWORD')]))
    ];

    $request = curl_init();
    curl_setopt($request, CURLOPT_URL, implode('/', [env('ZAMMAD_HOST'), $route]));
    curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($request, CURLOPT_POST, 1);
    curl_setopt($request, CURLOPT_TIMEOUT, 30);
    $result = curl_exec($request);
    curl_setopt($request, CURLOPT_POST, 0);

    $i = 0;
    do {
      curl_setopt($request, CURLOPT_URL, implode('?', [implode('/', [env('ZAMMAD_HOST'), $route]), implode('=', ['_', Carbon::now()->timestamp])]));
      $result = curl_exec($request);
      $result = json_decode($result, true);
      if ($result) {
        if (array_key_exists('result', $result)) {
          if (count($result['result']) > 0) {
            break;
          }
        }
      }
      $i++;
      sleep(2);
      if ($i > $max_request) {
        return [
          'error' => 'request limit number exceded'
        ];
        break;
      }
    } while (true);

    if (!$result) {
      return [
        'error' => 'no route to host'
      ];
    }

    curl_close($request);
    return $result;
  }

  //procedimiento de sincronizacion con el sistema de correspondencia
  public function sync_correspondence()
  {
   DB::beginTransaction();
    try{
      $query = "UPDATE users set habilitado = 0 where nivel <> 5 and nivel <> 4";
      $query = DB::connection('sigec')->select($query);
      $employees = Employee::where('active', true)->get();
      $new_register = 0;
      $update_register = 0;
      $active_without_contracts = 0;
      //se crea la sesion con el ldap
      $ldap = new Ldap();//return $ldap->get_entry(265);
      if($ldap->verify_open_port())
      {
        foreach($employees as $employee)
        {
          $query = "SELECT * from users where id = $employee->id";
          $query = DB::connection('sigec')->select($query);
          if($query)
          {
            if($employee->consultant())
            {
              if($employee->consultant_contracts->where('active',true)->count() > 0)
              {
                $entry = $ldap->get_entry($employee->id);
                $position = $entry['title'];
                $update = "UPDATE users set habilitado = 1, cargo = '$position'  where id = $employee->id";
                $update = DB::connection('sigec')->select($update);
                $update_register++;
              }
              else
              {
                $active_without_contracts++;
              }
            }
            else
            {
              if($employee->contracts->where('active',true)->count() > 0)
              {
                $entry = $ldap->get_entry($employee->id);
                $position = $entry['title'];
                $update = "UPDATE users set habilitado = 1, cargo = '$position'  where id = $employee->id";
                $update = DB::connection('sigec')->select($update);
                $update_register++;
              }
              else
              {
                $active_without_contracts++;
              }
            }
          }
          else
          {
            //condicional si el empleado cuenta con contratos activos para habilitarlo en el sistema de correspondencia
            if($employee->contracts->where('active',true)->count() > 0)
            {
              $ldap = new Ldap();
              $entry = $ldap->get_entry($employee->id);
              $id_office = $entry['employeeNumber'];
              $mosca = (substr($employee->first_name, 0, 1).substr($employee->second_name, 0, 1).substr($employee->last_name, 0, 1).substr($employee->mothers_last_name, 0, 1));
              $position = $entry['title'];
              $gender = $employee->gender=="M" ? "hombre" : "mujer";
              $username = $entry['uid'];
              $password = hash_hmac('sha256', $employee->identity_card, '2, 4, 6, 7, 9, 15, 20, 23, 25, 30');
              $mail = $entry['mail'];
              $insert = "INSERT INTO users(id, superior, id_oficina, dependencia, username, password, nombre, mosca, cargo, email, logins, fecha_creacion, habilitado, nivel, genero, prioridad, id_entidad, super, cedula_identidad)
              VALUES (".$employee->id.", 0, ".$id_office.", 1, '$username','$password', '".$employee->fullname()."', '$mosca', '$position', '$mail', 0, ".strtotime(Carbon::now()).", ".true.", 2, '$gender', 0, 24, 0, '".$employee->identity_card."')";
              $query = DB::connection('sigec')->select($insert);
              $role = "INSERT INTO roles_users(user_id, role_id) VALUES ($employee->id,2)";
              $query = DB::connection('sigec')->select($role);
              $role = "INSERT INTO roles_users(user_id, role_id) VALUES ($employee->id,1)";
              $query = DB::connection('sigec')->select($role);
              $tipo = "INSERT INTO usertipo(id_tipo, id_user) VALUES (3, $employee->id)";
              $query = DB::connection('sigec')->select($tipo);
              $tipo = "INSERT INTO usertipo(id_tipo, id_user) VALUES (4, $employee->id)";
              $query = DB::connection('sigec')->select($tipo);
              $new_register++;
            }
            else
              $active_without_contracts++;
          }
        }
      }
      DB::commit();
      $response = [
        'new register' => $new_register,
        'update register' => $update_register,
        'without contracts' => $active_without_contracts
      ];
      return $response;
    }catch(\Exception $e){
      DB::rollback();
      //throw $e;
        return ['message' => $e->getMessage()];
    }
  }
}
