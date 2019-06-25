<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Role;

/** @resource Role
 *
 * Resource to retrieve and show Role data
 */

class RoleController extends Controller
{
  /**
   * Display a listing of the roles data.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $roles = Role::get();
    foreach ($roles as $role) {
      $role->permissions = $role->permissions()->pluck('id');
    }
    return $roles;
  }

  /**
   * Display the specified role.
   *
   * @param  \App\Role  $role
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    return Role::with('permissions')->findOrFail($id);
  }
}
