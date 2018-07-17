<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployerNumber extends Model
{
  public function insurance_company() {
    return $this->belongsTo('App\InsuranceCompany');
  }

  public function company() {
    return $this->belongsTo('App\Company');
  }

  public function city() {
    return $this->hasOne('App\Company');
  }
}
