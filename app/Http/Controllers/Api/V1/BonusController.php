<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company;
use App\Contract;
use App\EmployeeBonus;
use App\BonusProcedure;

class BonusController extends Controller
{


  /**
   * Display the specified resource.
   *
   * @param  \App\Contract  $contract
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $procedure = BonusProcedure::findOrFail($id);
    $year = $procedure->year;
    $pay_date = $procedure->pay_date;
    $name = $procedure->name;

    $grouped_contracts = Contract::where(function ($query) use ($year) {
      $query
        ->orWhere(function ($q) use ($year) {
          $q
            ->orWhereYear('retirement_date', $year)
            ->orWhereYear('end_date', $year);
        })
        ->orWhere(function ($q) use ($year) {
          $q
            ->where('retirement_date', null)
            ->where('end_date', null)
            ->whereYear('start_date', '<=', $year);
        });
    })->leftjoin('employees as e', 'e.id', '=', 'contracts.employee_id')->select('contracts.*')->orderBy('e.last_name')->orderBy('e.mothers_last_name')->orderBy('start_date')->get()->groupBy('employee_id');

    $employees = [];
    foreach ($grouped_contracts as $e => $contracts) {
      $e = new EmployeeBonus($contracts, $year, $pay_date);
      if ($e->worked_days->months >= 3) {
        $employees[] = $e;
      }
    }

    $response = (object)array(
      "data" => [
        'company' => Company::select()->first(),
        'title' => (object)[
          'name' => 'PLANILLA DE AGUINALDOS',
          'year' => $year,
          'subtitle' => $name
        ],
        'pay_date' => $pay_date,
        'employees' => $employees,
      ]
    );

    $file_name = implode(" ", ['PLANILLA', $response->data['title']->subtitle, $year]) . ".pdf";

    return \PDF::loadView('payroll.bonus', $response->data)
      ->setOption('page-width', '216')
      ->setOption('page-height', '330')
      ->setOption('margin-left', '26')
      ->setOption('margin-right', '0')
      ->setOrientation('landscape')
      ->setOption('encoding', 'utf-8')
      ->setOption('footer-font-size', 5)
      ->setOption('footer-center', 'Impreso el ' . date('m/d/Y H:i'))
      ->setOption('encoding', 'utf-8')
      ->stream($file_name);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Contract  $contract
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Contract  $contract
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Contract  $contract
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {

  }
}
