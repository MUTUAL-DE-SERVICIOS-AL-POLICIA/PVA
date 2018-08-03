<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobSchedule extends Model {
	public function contracts() {
		return $this->belongsToMany(Contract::class);
	}
}
