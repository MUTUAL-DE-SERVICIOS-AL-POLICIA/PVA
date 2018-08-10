<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;

/** @resource Employee
 *
 * Resource to retrieve, store and edit Employee data
 */

class EmployeeController extends Controller
{
    /**
     * Display Employee's data.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Employee::with('city_identity_card')
                ->with('insurance_company')
                ->with('management_entity')
                ->with('city_birth')
                ->get());

    }

    /**
     * Store a newly Employee if no one found in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Employee::create($request->all());
        return response()->json([
            'status'  => 'success',
            'message' => 'Student successfully created.',
        ]);
        /*$employee                        = new Employee();
    $employee->insurance_company_id  = $request->insurance_company_id;
    $employee->city_identity_card_id = $request->city_identity_card_id;
    $employee->management_entity_id  = $request->management_entity_id;
    $employee->identity_card         = $request->identity_card;
    $employee->first_name            = $request->first_name;
    $employee->second_name           = $request->second_name;
    $employee->last_name             = $request->last_name;
    $employee->mothers_last_name     = $request->mothers_last_name;
    $employee->birth_date            = $request->birth_date;
    $employee->city_birth_id         = $request->city_birth_id;
    $employee->account_number        = $request->account_number;
    $employee->country_birth         = 'Bolivia';
    $employee->nua_cua               = $request->nua_cua;
    $employee->gender                = $request->gender;
    $employee->location              = $request->location;
    $employee->zone                  = $request->zone;
    $employee->street                = $request->street;
    $employee->number                = $request->number;
    $employee->status                = true;
    $employee->save();
    return response()->json([
    'status'  => 'success',
    'message' => 'Empleado creado correctamente',
    ]);*/
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Employee::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        return 22;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
