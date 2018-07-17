<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyAccount extends Model
{
  protected $fillable = ['account'];

  public function company()
  {
    return $this->belongsTo('App\Company');
  }
}
