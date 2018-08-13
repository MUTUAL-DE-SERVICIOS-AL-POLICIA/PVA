<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model {
	use SoftDeletes;
    protected $dates    = ['deleted_at'];
    public $timestamps  = false;
    public $guarded     = ['id'];
    protected $fillable = ['name', 'base_wage', 'active'];

	public function positions() {
		return $this->hasMany(Position::class);
	}

	public function payrolls() {
		return $this->hasMany(Payroll::class);
	}
}
