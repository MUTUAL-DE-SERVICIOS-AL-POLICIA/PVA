<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	public $timestamps = false;
	public $guarded = ['id'];
	protected $fillable = ['insurance_company_id', 'employee_id', 'position_id', 'contract_type_id', 'contract_mode_id', 'start_date', 'end_date', 'retirement_date', 'retirement_reason_id', 'active', 'rrhh_cite', 'rrhh_cite_date', 'performance_cite', 'insurance_number', 'contract_number', 'hiring_reference_number', 'description'];

	public function employee() {
		return $this->belongsTo(Employee::class);
	}

	public function position() {
		return $this->belongsTo(Position::class);
	}

	public function contract_type() {
		return $this->belongsTo(ContractType::class);
	}

	public function contract_mode() {
		return $this->belongsTo(ContractMode::class);
	}

	public function retirement_reason() {
		return $this->belongsTo(RetirementReason::class);
	}

	public function job_schedules() {
		return $this->belongsToMany(JobSchedule::class);
	}

	public function payrolls() {
		return $this->hasMany(Payroll::class);
	}

	public function insurance_company() {
		return $this->belongsTo(InsuranceCompany::class);
	}
}
