<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//use Illuminate\Database\Eloquent\SoftDeletes;

class Departure extends Model
{
  //use SoftDeletes;

  public $timestamps  = true;
  public $guarded     = ['id'];
  protected $fillable = ['contract_id', 'departure_reason_id', 'description', 'departure_date', 'return_date', 'departure_time', 'return_time', 'approved'];

  public function contract()
  {
    return $this->belongsTo(Contract::class);
  }

  public function departure_reason()
  {
    return $this->belongsTo(DepartureReason::class);
  }
}
