<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployerNumber extends Model
{
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
