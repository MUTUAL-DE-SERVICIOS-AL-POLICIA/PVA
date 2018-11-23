<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contract;
use App\EmployeeBonus;

class BonusController extends Controller
{
  /**
   * Display the specified resource.
   *
   * @param  \App\Contract  $contract
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request, $year)
  {
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

    $total = 0;
    $employees = [];
    foreach ($grouped_contracts as $e => $contracts) {
      $e = new EmployeeBonus($contracts, $year, $request['pay_date']);
      if ($e->worked_days->months >= 3) {
        $employees[] = $e;
        $total += $e->bonus;
      }
    }

    return response()->json((object)array(
      "data" => [
        'year' => $year,
        'pay_date' => $request['pay_date'],
        'total' => round($total, 2),
        'employees' => $employees,
      ]
    ));
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
