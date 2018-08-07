<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Month extends Model {
	public function procedures() {
		return $this->hasMany(Procedure::class);
	}
}
