<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserForm;
use App\User;
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
