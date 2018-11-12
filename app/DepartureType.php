<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartureType extends Model {
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['name', 'description'];

	public function departure_reasons() {
		return $this->hasMany(DepartureReason::class);
	}
}
