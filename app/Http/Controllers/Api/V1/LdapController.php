<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
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
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $ldap = new Ldap();

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
        if ($ldap->create_entry([
          'sn' => implode(' ', [$new_entry->last_name, $new_entry->mothers_last_name]),
          'givenName' => implode(' ', [$new_entry->first_name, $new_entry->second_name]),
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
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update($id)
  {
    $ldap = new Ldap();

    $employee = Employee::findOrFail($id);
    $last_contract = $employee->last_contract();

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
