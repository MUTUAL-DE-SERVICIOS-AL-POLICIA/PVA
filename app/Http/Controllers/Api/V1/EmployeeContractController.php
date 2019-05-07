<?php

namespace App\Http\Controllers\Api\V1;

use App;
use App\Contract;
use App\Employee;
use App\Http\Controllers\Controller;

/** @resource EmployeeContract
 *
 * Resource to retrieve, show, update and destroy EmployeeContract relation
 */

class EmployeeContractController extends Controller
{
  /**
   * Display a listing of the contracts related to employee.
   *
   * @param  \App\User  $employee_id
   * @return \Illuminate\Http\Response
   */
  public function get_contracts($employee_id)
  {
    $employee = Employee::findOrFail($employee_id);
    return $employee->contracts;
  }

  /**
   * Display the specified contract related to an employee.
   *
   * @param  \App\User  $employee_id
   * @param  \App\Role  $contract_id
   * @return \Illuminate\Http\Response
   */
  public function get_contract($employee_id, $contract_id)
  {
    $contract = Contract::where('employee_id', $employee_id)
      ->where('contract_id', $contract_id)
      ->first();
    return $contract;
  }

  /**
   * Display the last contract related to an employee.
   *
   * @param  \App\User  $employee_id
   * @return \Illuminate\Http\Response
   */
  public function get_last_contract($employee_id)
  {
    $employee = Employee::findOrFail($employee_id);
    if ($employee->consultant()) {
      $employee->last_consultant_contract()->consultant_position->position_group;
      $employee->last_consultant_contract()->consultant_position->charge;
      return $employee->last_consultant_contract();
    } else {
      $contract = $employee->last_contract();
      $contract->position->position_group;
      $contract->position->charge;
      return $contract;
    }
  }
}
