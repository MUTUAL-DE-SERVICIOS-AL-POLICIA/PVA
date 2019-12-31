<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployerContribution extends Model {
	use SoftDeletes;
	public $timestamps = true;
	public $guarded = ['id'];
	protected $dates = ['deleted_at'];
	protected $fillable = ['insurance_company', 'professional_risk', 'solidary', 'housing', 'active', 'living_expenses'];

	public function procedures() {
		return $this->hasMany(Procedure::class);
	}
}
