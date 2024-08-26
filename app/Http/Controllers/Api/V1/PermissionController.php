<?php

namespace App\Http\Controllers\Api\V1;

use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return Permission::get();
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
   * @param  \App\Permission  $permission
   * @return \Illuminate\Http\Response
   */
  public function show(Permission $permission)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Permission  $permission
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Permission $permission)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Permission  $permission
   * @return \Illuminate\Http\Response
   */
  public function destroy(Permission $permission)
  {
    //
  }
}
