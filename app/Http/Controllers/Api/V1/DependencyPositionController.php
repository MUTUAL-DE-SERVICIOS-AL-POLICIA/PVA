<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Position;

/** @resource PositionDependency
 *
 * Resource to retrieve, store, update and destroy PositionDependency data
 */

class DependencyPositionController extends Controller {
	/**
	 * Display the dependency or superior position group.
	 *
	 * @param  \App\Position $superior_id
	 * @return \Illuminate\Http\Response
	 */
	public function get_dependency($superior_id) {
		$position = Position::findOrFail($superior_id);
		return $position->depends_from;
	}

	/**
	 * Display a listing of the dependents.
	 *
	 * @param  \App\Position  $superior_id
	 * @return \Illuminate\Http\Response
	 */
	public function get_dependents($superior_id) {
		$position = Position::findOrFail($superior_id);
		return $position->dependents;
	}

	/**
	 * Display the specified dependent.
	 *
	 * @param  \App\Position  $superior_id
	 * @param  \App\Position  $dependent_id
	 * @return \Illuminate\Http\Response
	 */
	public function get_dependent($superior_id, $dependent_id) {
		$superior = Position::findOrFail($superior_id);
		$dependent = Position::findOrFail($dependent_id);
		if ($superior->dependents->contains($dependent)) {
			return $dependent;
		} else {
			return App::abort(404);
		}
	}

	/**
	 * Set position dependent.
	 *
	 * @param  \App\PositionGroup  $superior_id
	 * @param  \App\PositionGroup  $dependent_id
	 * @return \Illuminate\Http\Response
	 */
	public function set_dependent($superior_id, $dependent_id) {
		$superior = Position::findOrFail($superior_id);
		$dependent = Position::findOrFail($dependent_id);
		if (!$superior->dependents->contains($dependent)) {
			$superior->dependents()->attach($dependent);
			$superior->dependents;
			return $superior;
		} else {
			abort(403);
		}
	}

	/**
	 * Unset position group dependent.
	 *
	 * @param  \App\PositionGroup  $superior_id
	 * @param  \App\PositionGroup  $dependent_id
	 * @return \Illuminate\Http\Response
	 */
	public function unset_dependent($superior_id, $dependent_id) {
		$superior = Position::findOrFail($superior_id);
		$superior->dependents()->detach(Position::findOrFail($dependent_id));
		$superior->dependents;
		return $superior;
	}
}
