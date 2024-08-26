<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementEntity extends Model {
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['ovt_id', 'name'];

	public function employees() {
		return $this->hasMany(Employee::class);
	}
}
