<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceCheck extends Model
{
  protected $connection = 'attendance';
  protected $table = 'checkinout';
  public $timestamps = false;
  protected $fillable = ['USERID', 'CHECKTIME', 'CHECKTYPE', 'VERIFYCODE', 'SENSORID', 'Memoinfo', 'WorkCode', 'sn', 'UserExtFmt'];
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

  public function getUSERIDAttribute($value)
  {
    return utf8_decode($value);
  }

  public function getCHECKTIMEAttribute($value)
  {
    return utf8_decode($value);
  }

  public function getCHECKTYPEAttribute($value)
  {
    return utf8_decode($value);
  }

  public function getVERIFYCODEAttribute($value)
  {
    return utf8_decode($value);
  }

  public function getSENSORIDAttribute($value)
  {
    return utf8_decode($value);
  }

  public function getMemoinfoAttribute($value)
  {
    return utf8_decode($value);
  }

  public function getWorkCodeAttribute($value)
  {
    return utf8_decode($value);
  }

  public function getSnAttribute($value)
  {
    return utf8_decode($value);
  }

  public function getUserExtFmtAttribute($value)
  {
    return utf8_decode($value);
  }
}
