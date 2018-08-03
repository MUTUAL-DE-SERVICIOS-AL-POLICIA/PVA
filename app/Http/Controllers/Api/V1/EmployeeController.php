<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;

use Carbon\Carbon;

class EmployeeController extends Controller
{
   /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return response()->json(Employee::all());
  }

  /**
   * Display a listing of the resource formated.
   *
   * @return \Illuminate\Http\Response
   */
  public function list()
  {
    $employees = Employee::all();
    foreach ($employees as $key => $employee) {
      $row = [];
      $row['identity_card'] = $employee->identity_card.' '.$employee->city_identity_card->shortened;
      $row['fullname']      = $employee->fullName();
      $row['birth']         = Carbon::parse($employee->birth_date)->format('d/m/Y');
      $row['account']       = $employee->account_number;
      $row['afp']           = $employee->management_entity->name;
      $row['nua_cua']       = $employee->nua_cua;
      $row['gender']        = $employee->gender;
      

      $output[] = $row;
    }
    return response()->json($output);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
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
