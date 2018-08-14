<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDiscount extends Model {
	use SoftDeletes;
	public $timestamps = true;
	public $guarded = ['id'];
	protected $dates = ['deleted_at'];
	protected $fillable = ['elderly', 'common_risk', 'comission', 'solidary', 'national', 'rc_iva', 'active'];

	public function procedures() {
		return $this->hasMany(Procedure::class);
	}
}
