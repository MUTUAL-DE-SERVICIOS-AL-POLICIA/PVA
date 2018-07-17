<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PositionGroup extends Model
{
  public function depends_from()
  {
    return $this->belongsToMany(PositionGroup::class, 'dependency_position_group', 'dependent_id', 'superior_id');
  }

  public function dependents()
  {
    return $this->belongsToMany(PositionGroup::class, 'dependency_position_group', 'superior_id', 'dependent_id');
  }

  public function city()
  {
    return $this->belongsTo(Company::class);
  }
}
