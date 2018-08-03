<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	public function employee() {
		return $this->belongsTo(Employee::class);
	}

	public function position() {
		return $this->belongsTo(Position::class);
	}

	public function type() {
		return $this->belongsTo(ContractType::class);
	}

	public function mode() {
		return $this->belongsTo(ContractMode::class);
	}

	public function retirement_reason() {
		return $this->belongsTo(RetirementReason::class);
	}

	public function job_schedules() {
		return $this->belongsToMany(JobSchedule::class);
	}
}
