<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];

  public function positions()
  {
    return $this->hasMany(Position::class);
  }
}
