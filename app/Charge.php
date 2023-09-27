<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['name', 'base_wage', 'active'];

	public function positions() {
		return $this->hasMany(Position::class);
	}

	public function payrolls() {
		return $this->hasMany(Payroll::class);
	}
	
	public function previous_charge($charge_name){
		return Charge::where('name', $charge_name)
			->orderBy('created_at', 'desc')
			->skip(1) 
			->first();
	}
}
