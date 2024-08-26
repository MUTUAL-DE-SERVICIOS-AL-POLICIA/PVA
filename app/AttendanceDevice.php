<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ZKLibrary;
use Carbon;

class AttendanceDevice extends Model
{
  protected $connection = 'attendance';
  protected $table = 'machines';
  public $timestamps = false;
  protected $primaryKey = 'ID';
  public $guarded = ['ID'];
  protected $fillable = ['MachineAlias', 'IP', 'Port', 'MachineNumber', 'Enabled', 'sn'];

  public function checks()
  {
    return $this->hasMany(AttendanceCheck::class, 'SENSORID', 'MachineNumber')->select('USERID', 'CHECKTIME');
  }

  public function getIDAttribute($value)
  {
    return intval(utf8_decode($value));
  }

  public function getMachineAliasAttribute($value)
  {
    return utf8_decode($value);
  }

  public function getIPAttribute($value)
  {
    return utf8_decode($value);
  }

  public function getPortAttribute($value)
  {
    return intval(utf8_decode($value));
  }

  public function getMachineNumberAttribute($value)
  {
    return intval(utf8_decode($value));
  }

  public function getEnabledAttribute($value)
  {
    return boolval(utf8_decode($value));
  }

  public function getSnAttribute($value)
  {
    return utf8_decode($value);
  }

  public function setEnabledAttribute($value)
  {
    return intval($value);
  }

  public function get_time()
  {
    $zk = new ZKLibrary($this->IP, $this->Port);
    if ($zk->connect()) {
      $time = $zk->getTime();
      $zk->disconnect();
      $this->time = $time;
    }
  }

  public function set_time($time)
  {
    $zk = new ZKLibrary($this->IP, $this->Port);
    if ($zk->connect()) {
      $zk->setTime(Carbon::parse($time)->format('Y-m-d H:i:s'));
      $zk->disconnect();
      $this->get_time();
    }
  }
}