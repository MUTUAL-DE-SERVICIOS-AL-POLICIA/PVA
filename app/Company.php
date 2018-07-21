<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  protected $fillable = ['name', 'shortened'];

  public function accounts()
	{
		return $this->hasMany(CompanyAccount::class);
	}

	public function employer_numbers()
	{
		return $this->hasMany(EmployerNumber::class);
	}
}
