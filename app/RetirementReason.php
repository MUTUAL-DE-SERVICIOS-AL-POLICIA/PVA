<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RetirementReason extends Model {
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['ovt_id', 'name'];

	public function contracts() {
		return $this->hasMany(Contract::class);
	}
}
