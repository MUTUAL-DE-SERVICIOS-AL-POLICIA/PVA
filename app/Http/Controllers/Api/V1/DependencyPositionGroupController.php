<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\PositionGroup;

/** @resource PositionGroupDependency
 *
 * Resource to retrieve, store, update and destroy PositionGroupDependency data
 */

class DependencyPositionGroupController extends Controller {
	/**
	 * Display the dependency or superior position group.
	 *
	 * @param  \App\PositionGroup  $superior_id
	 * @return \Illuminate\Http\Response
	 */
	public function get_dependency($superior_id) {
		$position_group = PositionGroup::findOrFail($superior_id);
		return $position_group->depends_from;
	}

	/**
	 * Display a listing of the dependents.
	 *
	 * @param  \App\PositionGroup  $superior_id
	 * @return \Illuminate\Http\Response
	 */
	public function get_dependents($superior_id) {
		$position_group = PositionGroup::findOrFail($superior_id);
		return $position_group->dependents;
	}

	/**
	 * Display the specified dependent.
	 *
	 * @param  \App\PositionGroup  $superior_id
	 * @param  \App\PositionGroup  $dependent_id
	 * @return \Illuminate\Http\Response
	 */
	public function get_dependent($superior_id, $dependent_id) {
		$superior = PositionGroup::findOrFail($superior_id);
		$dependent = PositionGroup::findOrFail($dependent_id);
		if ($superior->dependents->contains($dependent)) {
			return $dependent;
		} else {
			return App::abort(404);
		}
	}

	/**
	 * Set position group dependent.
	 *
	 * @param  \App\PositionGroup  $superior_id
	 * @param  \App\PositionGroup  $dependent_id
	 * @return \Illuminate\Http\Response
	 */
	public function set_dependent($superior_id, $dependent_id) {
		$superior = PositionGroup::findOrFail($superior_id);
		$dependent = PositionGroup::findOrFail($dependent_id);
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
		$superior = PositionGroup::findOrFail($superior_id);
		$superior->dependents()->detach(PositionGroup::findOrFail($dependent_id));
		$superior->dependents;
		return $superior;
	}
}
