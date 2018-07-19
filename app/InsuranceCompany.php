<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsuranceCompany extends Model
{
  public function employer_number() {
    return $this->hasOne(EmployerNumber::class);
  }

  public function employees()
  {
    return $this->hasMany(Employee::class);
  }
}
