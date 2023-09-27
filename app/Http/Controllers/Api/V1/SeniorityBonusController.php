<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SeniorityBonus;
use App\MinimumSalary;

class SeniorityBonusController extends Controller
{
    /**
	 * Display a listing of Bonus.
	 *
	 * @return \Illuminate\Http\Response
	 */
  public function index()
  {
    return SeniorityBonus::orderBy('id','asc')->get();
  }

  /**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
  public function store(Request $request)
  {
    $request['active'] = true;
    $seniority_bonus = SeniorityBonus::create($request->all());
    return $seniority_bonus;
  }

  /**
 * Display the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
  public function show($id)
  {
    SeniorityBonus::findOrFail($id);
  }

  /**
 * Update a resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
  public function update(Request $request, $id)
  {
    $seniority_bonus = SeniorityBonus::findOrFail($id);
    $seniority_bonus->fill($request->all());
    $seniority_bonus->percentage = $seniority_bonus->percentage/100;
    $seniority_bonus->save();
  }

  public function destroy($id)
  {
    $seniority_bonus = SeniorityBonus::findOrFail($id);
    $seniority_bonus->delete();
    return $seniority_bonus;
  }

  public function getBonusCalculate()
  {
    $bonuses = SeniorityBonus::orderBy('id','asc')->get();
    if(MinimumSalary::where('active', true)->first())
      $minimun_salary_active = MinimumSalary::where('active', true)->first()->value;
    else
      $minimun_salary_active = 0;
    foreach($bonuses as $bonus)
    {
      $bonus->calculated = round((($bonus->percentage)*$minimun_salary_active),2);
      $bonus->percentage = $bonus->percentage*100;
    }
    return $bonuses;
  }

  // cálculo del bono antiguedad con el salario minimo anterior al actual
  public function getPreviousBonusCalculate()
  {
    $previous_bonuses = SeniorityBonus::orderBy('id','asc')->get();
    $previous_minimum_salary = MinimumSalary::orderBy('created_at', 'desc')->skip(1)->first();
    if($previous_minimum_salary)
      $previous_minimum_salary = $previous_minimum_salary->value;
    else
      $previous_minimum_salary = 0;    
    
    foreach($previous_bonuses as $bonus)
    {
      $bonus->calculated = round((($bonus->percentage)*$previous_minimum_salary),2);
      $bonus->percentage = $bonus->percentage*100;
    }
    return $previous_bonuses;
  }
}
