<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//use Illuminate\Database\Eloquent\SoftDeletes;

class Departure extends Model
{
  //use SoftDeletes;

  public $timestamps  = true;
  public $guarded     = ['id'];
  protected $fillable = ['departure_reason_id', 'description', 'approved', 'on_vacation', 'employee_id', 'director_id', 'departure', 'return'];

  function __construct()
  {
    $position = Position::with(['contracts' => function ($query) {
      $query->orderBy('created_at', 'ASC')->with('employee')->first();
    }])->first();
    if (count($position->contracts) > 0) $this->director_id = $position->contracts[0]->employee->id;
  }

  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  public function departure_reason()
  {
    return $this->belongsTo(DepartureReason::class);
  }

  public function director()
  {
    return $this->belongsTo(Employee::class, 'director_id', 'id');
  }
}
