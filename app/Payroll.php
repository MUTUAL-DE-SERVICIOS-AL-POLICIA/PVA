<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	public function procedure() {
		return $this->belongsTo(Procedure::class);
	}

	public function contract() {
		return $this->belongsTo(Contract::class);
	}

	public function charge() {
		return $this->belongsTo(Charge::class);
	}

	public function position_group() {
		return $this->belongsTo(PositionGroup::class);
	}

	public function position() {
		return $this->belongsTo(Position::class);
	}
}
