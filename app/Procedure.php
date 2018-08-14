<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procedure extends Model {
	use SoftDeletes;
    protected $dates    = ['deleted_at'];
    public $timestamps  = false;
    public $guarded     = ['id'];
    protected $fillable = ['year', 'month_id', 'employee_discount_id', 'employer_contribution_id', 'active'];

	public function month() {
		return $this->belongsTo(Month::class);
	}

	public function employee_discount() {
		return $this->belongsTo(EmployeeDiscount::class);
	}

	public function employer_contribution() {
		return $this->belongsTo(EmployerContribution::class);
	}

	public function payrolls() {
		return $this->hasMany(Payroll::class);
	}
}
