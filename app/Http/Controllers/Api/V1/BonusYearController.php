<?php

namespace App\Http\Controllers;

use App\BonusYear;
use Illuminate\Http\Request;

class BonusYearController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return BonusYear::get();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $bonus_year = BonusYear::create($request->all());
    $bonus_year->procedures;
    return $bonus_year;
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\BonusYear  $bonusYear
   * @return \Illuminate\Http\Response
   */
  public function show($year)
  {
    return BonusYear::findOrFail($year);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\BonusYear  $bonusYear
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $year)
  {
    $bonus_year = BonusYear::findOrFail($year);
    $bonus_year->fill($request->all());
    $bonus_year->save();
    return $bonus_year;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\BonusYear  $bonusYear
   * @return \Illuminate\Http\Response
   */
  public function destroy($year)
  {
    $bonus_year = BonusYear::findOrFail($year);
    $bonus_year->delete();
    return $bonus_year;
  }
}
