<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\ConsultantPayroll;
use App\ConsultantProcedure;
use Illuminate\Http\Request;
use App\Http\Requests\ConsultantPayrollForm;

class ConsultantPayrollController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return ConsultantPayroll::get();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ConsultantPayrollForm $request)
  {
    $procedure = ConsultantProcedure::findOrFail($request['consultant_procedure_id']);
    $year_shortened = substr(strval($procedure->year), -2);
    if (ConsultantPayroll::where('code', 'LIKE', "%-$year_shortened")->count() == 0) {
      $code = implode('-', ['C', str_pad(1, 3, '0', STR_PAD_LEFT), $year_shortened]);
    } else {
      $payroll = ConsultantPayroll::where('consultant_procedure_id', $request['consultant_procedure_id'])->where('charge_id', $request['charge_id'])->where('position_group_id', $request['position_group_id'])->where('consultant_position_id', $request['consultant_position_id'])->where('employee_id', $request['employee_id'])->first();
      if ($payroll) {
        $code = $payroll->code;
      } else {
        $last_code = ConsultantPayroll::where('consultant_procedure_id', $procedure->id)->orderBy('code', 'DESC')->select('code')->first();
        if ($last_code) {
          $last_code = intval(explode('-', $last_code->code)[1]);
        } else {
          $last_code = ConsultantPayroll::orderBy('code', 'DESC')->select('code')->first();
          $last_code = intval(explode('-', $last_code->code)[1]);
        }
        $code = implode('-', ['C', str_pad($last_code + 1, 3, '0', STR_PAD_LEFT), $year_shortened]);
      }
    }
    return ConsultantPayroll::create($request->all() + ['code' => $code]);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\ConsultantPayroll  $consultantPayroll
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    return ConsultantPayroll::findOrFail($id);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\ConsultantPayroll  $consultantPayroll
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $payroll = ConsultantPayroll::findOrFail($id);
    $payroll->fill($request->all());
    $payroll->save();
    return $payroll;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\ConsultantPayroll  $consultantPayroll
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $payroll = ConsultantPayroll::findOrFail($id);
    $payroll->delete();
    return $payroll;
  }

  /**
   * get payroll especific contract
   *
   * @param  int  $contract_id
   * @return \Illuminate\Http\Response
   */
  public function getPayrollContract($contract_id)
  {
    return response()->json([
      'count' => ConsultantPayroll::where('consultant_contract_id', $contract_id)->count()
    ]);
  }
}
