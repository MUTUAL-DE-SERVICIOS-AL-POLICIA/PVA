<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyAccountNumber extends Model
{
  protected $fillable = ['account_number'];

  public function company()
  {
    return $this->belongsTo(Company::class, 'company_id', 'id');
  }
}
