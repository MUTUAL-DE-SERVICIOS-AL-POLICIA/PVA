<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model {
	public $timestamps = false;
	public $guarded = ['id'];
	protected $fillable = ['name', 'shortened', 'tax_number'];

	public function accounts() {
		return $this->hasMany(CompanyAccount::class);
	}

	public function employer_numbers() {
		return $this->hasMany(EmployerNumber::class);
	}
}
