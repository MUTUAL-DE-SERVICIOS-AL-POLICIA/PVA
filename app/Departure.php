<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//use Illuminate\Database\Eloquent\SoftDeletes;

class Departure extends Model
{
  //use SoftDeletes;

  public $timestamps  = true;
  public $guarded     = ['id'];
  protected $fillable = ['departure_reason_id', 'description', 'approved', 'on_vacation', 'employee_id', 'departure', 'return', 'cite'];

  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  public function departure_reason()
  {
    return $this->belongsTo(DepartureReason::class);
  }
}
