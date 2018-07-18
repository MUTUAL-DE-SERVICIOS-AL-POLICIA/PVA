<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];

  public function depends_from()
  {
    return $this->belongsToMany(PositionGroup::class, 'dependency_positions', 'dependent_id', 'superior_id');
  }

  public function dependents()
  {
    return $this->belongsToMany(PositionGroup::class, 'dependency_positions', 'superior_id', 'dependent_id');
  }

  public function charge()
  {
    return $this->belongsTo(Charge::class);
  }

  public function position_group()
  {
    return $this->belongsTo(PositionGroup::class);
  }
}
