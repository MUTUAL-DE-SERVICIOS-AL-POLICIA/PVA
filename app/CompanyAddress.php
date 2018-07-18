<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

  public function city()
	{
		return $this->belongsTo(City::class);
  }
  
  public function position_groups()
	{
		return $this->hasMany(PositionGroup::class);
	}
}
