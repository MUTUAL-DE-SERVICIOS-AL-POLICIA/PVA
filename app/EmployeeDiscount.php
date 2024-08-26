<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDiscount extends Model {
	use SoftDeletes;
	public $timestamps = true;
	public $guarded = ['id'];
	protected $dates = ['deleted_at'];
	protected $fillable = ['elderly', 'common_risk', 'comission', 'solidary', 'national_limits', 'national_percentages', 'rc_iva', 'active'];

  public function getNationalLimitsAttribute()
  {
    return json_decode($this->attributes['national_limits']);
  }

  public function getNationalPercentagesAttribute()
  {
    return json_decode($this->attributes['national_percentages']);
  }

  public function setNationalLimitsAttribute($value)
  {
    return $this->attributes['national_limits'] = json_encode($value);
  }

  public function setNationalPercentagesAttribute($value)
  {
    return $this->attributes['national_percentages'] = json_encode($value);
  }

	public function procedures() {
		return $this->hasMany(Procedure::class);
  }
}
