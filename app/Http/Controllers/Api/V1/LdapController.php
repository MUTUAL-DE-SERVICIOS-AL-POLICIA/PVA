<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Employee;
use App\User;
use Ldap;

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
      if ($last_contract) {
        $givenName = $new_entry->first_name;
        if ($new_entry->second_name) $givenName .= ' ' . $new_entry->second_name;
        $sn = $new_entry->last_name;
        if ($new_entry->mothers_last_name) $sn .= ' ' . $new_entry->mothers_last_name;

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
      } else {
        $added[$key] = $new_entry;
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

    return response()->json([
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
        'removed' => $success_removed
      ],
    ]);
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
}
