<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];

  public function charge()
  {
    return $this->belongsTo(Charge::class);
  }

  public function position_group()
  {
    return $this->belongsTo(PositionGroup::class);
  }
}
