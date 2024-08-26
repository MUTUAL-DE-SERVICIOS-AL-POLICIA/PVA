<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Month extends Model {
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['order', 'name', 'shortened'];

	public function procedures() {
		return $this->hasMany(Procedure::class);
	}
}
