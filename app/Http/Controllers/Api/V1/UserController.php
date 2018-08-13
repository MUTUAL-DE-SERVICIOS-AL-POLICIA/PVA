<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserForm;
use App\User;
use Illuminate\Http\Request;

/** @resource User
 *
 * Resource to retrieve, show, update and destroy User data
 */

class UserController extends Controller {
	/**
	 * Display User's data.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return User::get();
	}

	/**
	 * Display the specified user.
	 *
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		return User::findOrFail($id);
	}

	/**
	 * Update the specified user in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function update(UserForm $request, $id) {
		$user = User::findOrFail($id);
		$user->fill($request->all());
		$user->password = bcrypt($request->all()['password']);
		$user->save();
		return $user;
	}

	/**
	 * Remove the specified user from storage.
	 *
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$user = User::findOrFail($id);
		$user->delete();
		return $user;
	}
}
