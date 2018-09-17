<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserForm;
use App\Http\Requests\UserEmployeeForm;
use App\User;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
		return User::get();
	}

	/**
	 * Stores a user.
	 *
	 * @param  \App\Employee  $employee
	 * @return \Illuminate\Http\Response
	 */
	public function store(UserEmployeeForm $request)
	{
		if (!env("ADLDAP_AUTHENTICATION")) {
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
			abort(400);
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
		return User::findOrFail($id);
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
		$user = User::findOrFail($id);
		$user->delete();
		return $user;
	}
}
