<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//use Illuminate\Database\Eloquent\SoftDeletes;

class Departure extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  public $timestamps  = true;
  public $guarded     = ['id'];
  protected $fillable = ['departure_reason_id', 'description', 'approved', 'employee_id', 'departure', 'return', 'cite', 'code'];

  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  public function departure_reason()
  {
    return $this->belongsTo(DepartureReason::class);
  }

  
  public function sum_days($departure_id)
  {
    return VacationTime::where('departure_id', $departure_id)
      ->whereNull('deleted_at')
      ->sum('day');
  }

  public function vacation_time()
  {
    return $this->HasMany(VacationTime::class);
  }
}
