<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyAddress extends Model {
	use SoftDeletes;

	public $timestamps = true;
	public $guarded = ['id'];
	protected $dates = ['deleted_at'];
	protected $fillable = ['address', 'city_id'];

	public function city() {
		return $this->belongsTo(City::class);
	}

	public function position_groups() {
		return $this->belongsToMany(PositionGroup::class);
	}

	public function active() {
		return CompanyAddress::where('active', true)->first();
	}
}
