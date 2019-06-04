<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceCheck extends Model
{
  protected $connection = 'attendance';
  protected $table = 'checkinout';
  public $timestamps = false;
  protected $fillable = [];
  protected $primaryKey = null;
  public $incrementing = false;

  public function user()
  {
    return $this->belongsTo(AttendanceUser::class, 'USERID', 'USERID');
  }
}
