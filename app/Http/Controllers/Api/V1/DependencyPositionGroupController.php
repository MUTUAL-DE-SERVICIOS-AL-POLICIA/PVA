<?php

namespace App\Http\Controllers;

use App;
use App\PositionGroup;

class DependencyPositionGroupController extends Controller {
	public function get_dependency($superior_id) {
		$position_group = PositionGroup::findOrFail($superior_id);
		return $position_group->depends_from;
	}

	public function get_dependents($superior_id) {
		$position_group = PositionGroup::findOrFail($superior_id);
		return $position_group->dependents;
	}

	public function get_dependent($superior_id, $dependent_id) {
		$superior = PositionGroup::findOrFail($superior_id);
		$dependent = PositionGroup::findOrFail($dependent_id);
		if ($superior->dependents->contains($dependent)) {
			return $dependent;
		} else {
			return App::abort(404);
		}
	}

	public function set_dependent($superior_id, $dependent_id) {
		$superior = PositionGroup::findOrFail($superior_id);
		$dependent = PositionGroup::findOrFail($dependent_id);
		if (!$superior->dependents->contains($dependent)) {
			$superior->dependents()->attach($dependent);
			$superior->dependents;
			return $superior;
		} else {
			return App::abort(400);
		}
	}

	public function unset_dependent($superior_id, $dependent_id) {
		$superior = PositionGroup::findOrFail($superior_id);
		$superior->dependents()->detach(PositionGroup::findOrFail($dependent_id));
		$superior->dependents;
		return $superior;
	}
}
