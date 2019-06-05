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

  function __construct()
  {
    $this->CHECKTYPE = 'I';
    $this->WorkCode = 0;
    $this->UserExtFmt = 1;
  }

  public function user()
  {
    return $this->belongsTo(AttendanceUser::class, 'USERID', 'USERID');
  }

  public function device()
  {
    return $this->belongsTo(AttendanceDevice::class, 'SENSORID', 'MachineNumber');
  }
}
