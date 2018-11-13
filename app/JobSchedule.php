<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobSchedule extends Model
{
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['start_hour', 'start_minutes', 'end_hour', 'end_minutes', 'workdays'];
	protected $casts = ['workdays' => 'array'];

	public function contracts()
	{
		return $this->belongsToMany(Contract::class);
	}

	public function consultant_contracts()
	{
		return $this->belongsToMany(ConsultantContract::class);
	}
}
