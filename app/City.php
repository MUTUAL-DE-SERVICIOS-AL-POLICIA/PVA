<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
  public function employer_number()
	{
		return $this->belongsTo(EmployerNumber::class);
	}

	public function addresses()
	{
		return $this->hasMany(Address::class);
	}

	public function employees_with_identity_cards()
	{
		return $this->hasMany(Employee::class, 'city_identity_card_id', 'id');
	}

	public function employees_with_births()
	{
		return $this->hasMany(Employee::class, 'city_birth_id', 'id');
	}
}
