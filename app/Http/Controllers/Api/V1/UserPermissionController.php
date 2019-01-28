<?php

namespace App\Http\Controllers\Api\V1;

use App\Permission;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserPermissionController extends Controller
{
  /**
   * Display a listing of the permissions related to user.
   *
   * @param  \App\User  $user_id
   * @return \Illuminate\Http\Response
   */
  public function get_permissions($user_id)
  {
    $user = User::findOrFail($user_id);
    return $user->allPermissions();
  }

  /**
   * Display the specified permission related to a user.
   *
   * @param  \App\User  $user_id
   * @param  \App\Permission  $permission_id
   * @return \Illuminate\Http\Response
   */
  public function get_permission($user_id, $permission_id)
  {
    $permission = Permission::findOrFail($permission_id);
    if ($user->can($permission->name)) {
      return $permission;
    }
  }

  /**
   * Attach permission to a user.
   *
   * @param  \App\User  $user_id
   * @param  \App\Permission  $permission_id
   * @return \Illuminate\Http\Response
   */
  public function set_permission($user_id, $permission_id)
  {
    $user = User::findOrFail($user_id);
    $permission = Permission::findOrFail($permission_id);
    if ($user->username != 'admin') {
      if (!$user->can($permission->name)) {
        $user->attachPermission($permission);
      }
    }
    return $user->allPermissions();
  }

  /**
   * Detach permission to a user.
   *
   * @param  \App\User  $user_id
   * @param  \App\Permission  $permission_id
   * @return \Illuminate\Http\Response
   */
  public function unset_permission($user_id, $permission_id)
  {
    $user = User::findOrFail($user_id);
    $permission = Permission::findOrFail($permission_id);
    if ($user->username != 'admin') {
      $user->detachPermission($permission);
    }
    $user->allPermissions();
  }
}
