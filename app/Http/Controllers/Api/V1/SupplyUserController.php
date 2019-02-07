<?php

namespace App\Http\Controllers\Api\V1;

use App\SupplyDepartment;
use App\SupplyUser;
use App\Employee;
use Util;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplyUserController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return SupplyUser::get();
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
   * @param  \App\SupplyUser  $supplyUser
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request, $id)
  {
    $employee = Employee::findOrFail($id);
    $user = SupplyUser::where('ci', $employee->identity_card)->orWhere('name', Util::fullName($employee, 'uppercase', 'name_first'))->latest()->first();
    if ($user) {
      if ($employee->consultant()) {
        if (trim(preg_replace('/[\s]+/', ' ', $user->title)) == mb_strtoupper($employee->last_consultant_contract()->consultant_position->name) && $user->department->name == $employee->last_consultant_contract()->consultant_position->position_group->name) {
          if (!$user->ci) {
            $user->ci = $employee->identity_card;
            $user->save();
          }
          if ($user->code != $employee->id) {
            $user->code = $employee->id;
            $user->save();
          }
        } else {
          $user = $this->create_user($employee, $request['username']);
        }
      } else {
        if (trim(preg_replace('/[\s]+/', ' ', $user->title)) == mb_strtoupper($employee->last_contract()->position->name) && $user->department->name == $employee->last_contract()->position->position_group->name) {
          if (!$user->ci) {
            $user->ci = $employee->identity_card;
            $user->save();
          }
          if ($user->code != $employee->id) {
            $user->code = $employee->id;
            $user->save();
          }
        } else {
          $user = $this->create_user($employee, $request['username']);
        }
      }
    } else {
      $user = $this->create_user($employee, $request['username']);
    }
    return $user->requests()->where('invalidate', 0)->orderBy('created_at', 'DESC')->get();
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\SupplyUser  $supplyUser
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\SupplyUser  $supplyUser
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }

  private function create_user($employee)
  {
    return SupplyUser::create([
      'code' => $employee->id,
      'name' => Util::fullName($employee, 'uppercase', 'name_first'),
      'title' => $employee->consultant() ? mb_strtoupper($employee->last_consultant_contract()->consultant_position->name) : mb_strtoupper($employee->last_contract()->position->name),
      'ci' => $employee->identity_card,
      'status' => 1,
      'department_id' => $employee->consultant() ? SupplyDepartment::where('name', $employee->last_consultant_contract()->consultant_position->position_group->name)->first()->id : SupplyDepartment::where('name', $employee->last_contract()->position->position_group->name)->first()->id
    ]);
  }
}
