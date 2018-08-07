<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PositionGroup extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	public function depends_from() {
		return $this->belongsToMany(PositionGroup::class, 'dependency_position_group', 'dependent_id', 'superior_id');
	}

	public function dependents() {
		return $this->belongsToMany(PositionGroup::class, 'dependency_position_group', 'superior_id', 'dependent_id');
	}

	public function addresses() {
		return $this->belongsToMany(CompanyAddress::class);
	}

	public function positions() {
		return $this->hasMany(Position::class);
	}
}
