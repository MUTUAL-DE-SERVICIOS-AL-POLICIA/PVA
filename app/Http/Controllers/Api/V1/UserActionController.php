<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\UserAction;

/** @resource UserAction
 *
 * Resource to retrieve, show and destroy UserAction data
 */

class UserActionController extends Controller {
	/**
	 * Display a listing of the users actions.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return UserAction::get();
	}

	/**
	 * Display the specified action.
	 *
	 * @param  \App\UserAction  $userAction
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$user = UserAction::findOrFail($id);
		$user->fill($request->all());
		$user->password = bcrypt($request->all()['password']);
		$user->save();
		return $user;
	}

	/**
	 * Remove the specified action from storage.
	 *
	 * @param  \App\UserAction  $userAction
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$user = UserAction::findOrFail($id);
		$user->delete();
		return $user;
	}
}
