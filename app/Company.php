<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  protected $fillable = ['name', 'shortened'];

  public function account_numbers()
	{
		return $this->hasMany(CompanyAccountNumber::class, 'company_id', 'id');
	}
}
