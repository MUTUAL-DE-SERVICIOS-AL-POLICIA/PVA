<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobSchedule extends Model {
    public $timestamps  = false;
    public $guarded     = ['id'];
    protected $fillable = ['start_hour', 'start_minutes', 'end_hour', 'end_minutes'];

	public function contracts() {
		return $this->belongsToMany(Contract::class);
	}
}
