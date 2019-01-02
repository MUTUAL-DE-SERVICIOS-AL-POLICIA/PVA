<?php

namespace App\Http\Controllers\Api\V1;

use App\Contract;
use App\EmployerNumber;
use App\Http\Controllers\Controller;
use App\Procedure;
use Illuminate\Http\Request;

/** @resource Contract
 *
 * Resource to retrieve, store and update contracts data
 */
class ContractController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return Contract::with('job_schedules', 'employee', 'insurance_company', 'employee.city_identity_card', 'position', 'position.charge', 'position.position_group', 'contract_type', 'contract_mode', 'retirement_reason')->withCount('payrolls')->orderBy('end_date', 'ASC')->get();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if ($this->contract_between_dates($request['employee_id'], $request['start_date'], $request['end_date']) == 0) {
      $contract = Contract::create($request->all());
      $contract->job_schedules()->attach($request->schedule['id']);
      if ($request->schedule['id'] == 1) {
        $contract->job_schedules()->attach(2);
      }
      return $contract;
    }
    abort(403);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    return Contract::findOrFail($id);
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
    $contract = Contract::findOrFail($id);
    // if ($this->contract_between_dates($contract->employee_id, $request['start_date'], $request['end_date'], $id) == 0) {
      $contract->fill($request->all());
      $contract->save();
      if ($request->schedule['id']) {
        $contract->job_schedules()->detach();
        $contract->job_schedules()->attach($request->schedule['id']);
        if ($request->schedule['id'] == 1) {
          $contract->job_schedules()->attach(2);
        }
      }
      return $contract;
    // }
    // abort(403);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $contract = Contract::findOrFail($id);
    $contract->delete();
    return $contract;
  }

  /**
   * PDF the specified resource from storage.
   *
   * @param  int  $id
   * @return pdf
   */
  function print($id, $type)
  {
    $headerHtml = view()->make('partials.head')->render();
    $pageWidth = '216';
    $pageHeight = '330';
    $pageMargins = [30, 25, 40, 30];
    $pageName = 'contrato.pdf';
    $data = [
      'contract' => Contract::findOrFail($id),
      'mae' => Contract::where([['position_id', '1'], ['active', 'true']])->first(),
      'employer_number' => EmployerNumber::where('insurance_company_id', '1')->first(),
    ];
    if ($type != 'printEventual') {
      $headerHtml = '';
      $pageWidth = '202';
      $pageHeight = '130';
      $pageMargins = [10, 11, 12, 11];
      $pageName = 'seguro.pdf';
    }
    return \PDF::loadView('contract.' . $type, $data)
      ->setOption('header-html', $headerHtml)
      ->setOption('page-width', $pageWidth)
      ->setOption('page-height', $pageHeight)
      ->setOption('margin-top', $pageMargins[0])
      ->setOption('margin-right', $pageMargins[1])
      ->setOption('margin-bottom', $pageMargins[2])
      ->setOption('margin-left', $pageMargins[3])
      ->setOption('encoding', 'utf-8')
      ->stream($pageName);
  }

  /**
   * Display the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function positionFree($position_id)
  {
    $contract = Contract::where([['position_id', $position_id], ['active', true]])->first();
    return $contract;
  }

  /**
   * Display the contract's list with valid date from procedure.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function valid_date($procedure_id)
  {
    $procedure = Procedure::findOrFail($procedure_id);
    $contracts = new Contract();
    $contracts = $contracts->valid_date($procedure->year, $procedure->month->order);
    foreach ($contracts as $contract) {
      $contract->employee;
      $contract->employee->city_identity_card;
      $contract->position->position_group;
      $contract->position->charge;
    }
    return $contracts;
  }

  /**
   * Display the last contract row.
   *
   * @param  int  $employee_id
   * @return \Illuminate\Http\Response
   */
  public function last_contract($employee_id)
  {
    return Contract::with('employee', 'employee.city_identity_card', 'position', 'position.position_group', 'job_schedules', 'contract_type.departure_reasons')->where('employee_id', $employee_id)->orderBy('end_date', 'desc')->select('contracts.active as act', '*')->first();
  }

  /**
   * Display the contract's list.
   *
   * @param  int  $position_id
   * @return \Illuminate\Http\Response
   */
  public function contract_position_group($position_id)
  {
    return Contract::with('employee', 'employee.city_identity_card', 'position', 'position.position_group')
      ->join('positions', 'positions.id', '=', 'contracts.position_id')
      ->join('employees', 'employees.id', '=', 'contracts.employee_id')
      ->where('positions.position_group_id', $position_id)
      ->where('contracts.active', true)
      ->get();
  }

  private function contract_between_dates($employee_id, $start_date, $end_date, $contract_id = null)
  {
    if ($contract_id) {
      return Contract::where('id', '!=', $contract_id)->where('employee_id', $employee_id)->where(function ($q) use ($start_date, $end_date) {
        $q
          ->whereBetween('start_date', [$start_date, $end_date])
          ->orWhereBetween('end_date', [$start_date, $end_date])
          ->orWhereBetween('retirement_date', [$start_date, $end_date]);
      })->count();
    } else {
      return Contract::where('employee_id', $employee_id)->where(function ($q) use ($start_date, $end_date) {
        $q
          ->whereBetween('start_date', [$start_date, $end_date])
          ->orWhereBetween('end_date', [$start_date, $end_date])
          ->orWhereBetween('retirement_date', [$start_date, $end_date]);
      })->count();
    }
  }
}
