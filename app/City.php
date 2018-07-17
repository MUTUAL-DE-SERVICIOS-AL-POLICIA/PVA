<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
  public function employer_number()
	{
		return $this->belongsTo('App\EmployerNumber');
	}
}
