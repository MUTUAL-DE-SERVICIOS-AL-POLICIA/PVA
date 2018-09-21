<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\UserAction;

/** @resource UserAction
 *
 * Resource to retrieve, show and destroy UserAction data
 */

class UserActionController extends Controller
{
	/**
	 * Display a listing of the users actions.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return UserAction::with('user')->orderBy('created_at', 'DESC')->get();
	}

	/**
	 * Display the specified action.
	 *
	 * @param  \App\UserAction  $userAction
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return UserAction::findOrFail($id);
	}

	/**
	 * Remove the specified action from storage.
	 *
	 * @param  \App\UserAction  $userAction
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$user_action = UserAction::findOrFail($id);
		$user_action->delete();
		return $user_action;
	}
}
