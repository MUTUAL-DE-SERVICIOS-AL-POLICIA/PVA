<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployerNumber extends Model {
	use SoftDeletes;

	public $timestamps = true;
	public $guarded = ['id'];
	protected $dates = ['deleted_at'];
	protected $fillable = ['number', 'insurance_company_id', 'company_id'];
	protected $hidden = ['company_id'];

	public function insurance_company() {
		return $this->belongsTo(InsuranceCompany::class);
	}

	public function company() {
		return $this->belongsTo(Company::class);
	}

	public function city() {
		return $this->hasOne(Company::class);
	}
}
