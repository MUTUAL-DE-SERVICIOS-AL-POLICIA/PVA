<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartureReason extends Model
{
    public $timestamps  = true;
    public $guarded     = ['id'];
    protected $fillable = ['name', 'departure_type_id', 'name', 'day', 'hour', 'each', 'pay', 'description'];

    public function departure_type()
    {
        return $this->belongsTo(DepartureType::class);
    }

    public function contract_types () {
		return $this->belongsToMany(ContractType::class);
	}
}