<?php

namespace App\Http\Controllers;

use App;
use App\Contract;
use App\Employee;

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
        return $employee->contract;
    }

    /**
     * Display the specified contract related to a employee.
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
}
