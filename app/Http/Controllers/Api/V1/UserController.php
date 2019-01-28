<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserForm;
use App\Http\Requests\UserEmployeeForm;
use App\User;
use App\UserAction;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Ldap;
/** @resource User
 *
 * Resource to retrieve, show, update and destroy User data
 */

class UserController extends Controller
{
	/**
	 * Display User's data.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$users = User::where('active', true)->with('roles')->whereNotNull('employee_id')->with('employee')->orderBy('username')->get();
		foreach ($users as $i => $user) {
			$permissions_list = $user->allPermissions()->pluck('id');
			unset($user['permissions']);
			$user->permissions = $permissions_list;
		}
		return $users;
	}

	/**
	 * Stores a user.
	 *
	 * @param  \App\Employee  $employee
	 * @return \Illuminate\Http\Response
	 */
	public function store(UserEmployeeForm $request)
	{
		if (!env("LDAP_AUTHENTICATION")) {
			$employee = Employee::findOrFail(request("employee_id"));
			$user = new User();
			$user->username = "";
			$user->username .= substr($employee->first_name, 0, 1);
			if ($employee->last_name != null) {
				$user->username .= explode(" ", $employee->last_name)[0];
			} else {
				$user->username .= explode(" ", $employee->mothers_last_name)[0];
			}
			$user->username = strtolower($user->username);
			$user->password = Hash::make($employee->identity_card);
			$user->save();
			return $user;
		} else {
			$employee = Employee::findOrFail(request("employee_id"));
			$ldap = new Ldap();
			$user = new User();
			$entry = $ldap->get_entry($employee->id);
			$username = $entry['uid'];

			if ($username) {
				$user->username = $username;
				$user->employee_id = $employee->id;
				$user->position = $entry['title'];
				$user->password = Hash::make($username);
				$user->save();

				return $user;
			}
			abort(409);
		}
	}

	/**
	 * Display the specified user.
	 *
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$user = User::findOrFail($id);
		if ($user->active) {
			$user->employee;
			return $user;
		}
		abort(404);
	}

	/**
	 * Update the specified user in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function update(UserForm $request, $id)
	{
		$user = User::findOrFail($id);
		if (!Hash::check(request('oldPassword'), Auth::user()->password) || $user->id != $id) {
			return response()->json([
				'message' => 'Contraseña incorrecta',
				'errors' => [
					'type' => ['Contraseña anterior incorrecta'],
				],
			], 400);
		} else {
			$user = User::find(Auth::user()->id);
			$user->password = Hash::make(request('newPassword'));
			$user->save();
			return $user;
		}
	}

	/**
	 * Remove the specified user from storage.
	 *
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if (UserAction::where('user_id', $id)->count() == 0) {
			$user = User::findOrFail($id);
			if ($user->username != 'admin') {
				$user->active = false;
				$user->save();
				return $user;
			} else {
				return response()->json([
					'message' => 'Bad Request Error',
					'errors' => [
						'type' => ['Este usuario no se puede eliminar'],
					],
				], 400);
			}
		} else {
			return response()->json([
				'message' => 'Bad Request Error',
				'errors' => [
					'type' => ['El usuario tiene acciones registradas'],
				],
			], 400);
		}
	}
}
