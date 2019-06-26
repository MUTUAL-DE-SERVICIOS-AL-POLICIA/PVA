<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceDevice extends Model
{
  protected $connection = 'attendance';
  protected $table = 'machines';
  public $timestamps = false;
  protected $fillable = [];
  protected $primaryKey = 'MachineNumber';

  public function checks()
  {
    return $this->hasMany(AttendanceCheck::class, 'SENSORID', 'MachineNumber')->select('USERID', 'CHECKTIME');
  }
}
