<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsuranceCompany extends Model {
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['ovt_id', 'name', 'shortened'];

	public function employer_number() {
		return $this->hasOne(EmployerNumber::class);
	}

	public function contracts() {
		return $this->hasMany(Contract::class);
	}
}
