<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceUser extends Model
{
  protected $connection = 'attendance';
  protected $table = 'userinfo';
  public $timestamps = false;
  protected $fillable = [];
  protected $primaryKey = 'userid';

  public function checks()
  {
    return $this->hasMany(AttendanceCheck::class, 'USERID', 'USERID')->select('checktime');
  }
}
