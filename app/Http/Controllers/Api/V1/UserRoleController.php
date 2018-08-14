<?php

namespace App\Http\Controllers\Api\V1;

use App;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;

/** @resource UserRole
 *
 * Resource to retrieve, show, update and destroy User-Role relation
 */

class UserRoleController extends Controller {
	/**
	 * Display a listing of the roles related to user.
	 *
	 * @param  \App\User  $user_id
	 * @return \Illuminate\Http\Response
	 */
	public function get_roles($user_id) {
		$user = User::findOrFail($user_id);
		return $user->roles;
	}

	/**
	 * Display the specified role related to a user.
	 *
	 * @param  \App\User  $user_id
	 * @param  \App\Role  $role_id
	 * @return \Illuminate\Http\Response
	 */
	public function get_role($user_id, $role_id) {
		$user = User::findOrFail($user_id);
		$role = Role::findOrFail($role_id);
		if ($user->hasRole($role->name)) {
			return $role;
		} else {
			return App::abort(404);
		}
	}

	/**
	 * Attach role to a user.
	 *
	 * @param  \App\User  $user_id
	 * @param  \App\Role  $role_id
	 * @return \Illuminate\Http\Response
	 */
	public function set_role($user_id, $role_id) {
		$user = User::findOrFail($user_id);
		$role = Role::findOrFail($role_id);
		$user->attachRole($role);
		$user->roles;
		return $user;
	}

	/**
	 * Detach role to a user.
	 *
	 * @param  \App\User  $user_id
	 * @param  \App\Role  $role_id
	 * @return \Illuminate\Http\Response
	 */
	public function unset_role($user_id, $role_id) {
		$user = User::findOrFail($user_id);
		$role = Role::findOrFail($role_id);
		$user->detachRole($role);
		$user->roles;
		return $user;
	}
}
