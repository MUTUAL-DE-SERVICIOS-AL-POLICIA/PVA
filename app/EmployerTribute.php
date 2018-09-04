<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployerTribute extends Model {
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['minimun_salary', 'ufv'];

	public function procedures() {
		return $this->hasMany(Procedure::class);
	}
}
