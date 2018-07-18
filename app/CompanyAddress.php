<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
  public function city()
	{
		return $this->belongsTo(City::class);
  }
  
  public function position_groups()
	{
		return $this->hasMany(PositionGroup::class);
	}
}
