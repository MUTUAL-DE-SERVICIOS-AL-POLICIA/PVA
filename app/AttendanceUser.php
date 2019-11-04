<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceUser extends Model
{
  protected $connection = 'attendance';
  protected $table = 'userinfo';
  public $timestamps = false;
  protected $primaryKey = 'USERID';
  public $guarded = ['USERID'];
  protected $fillable = ['BADGENUMBER', 'SSN', 'NAME'];

  public function checks()
  {
    return $this->hasMany(AttendanceCheck::class, 'USERID', 'USERID')->select('checktime');
  }

  public function getUSERIDAttribute($value)
  {
    return utf8_decode($value);
  }

  public function getBADGENUMBERAttribute($value)
  {
    return utf8_decode($value);
  }

  public function getSSNAttribute($value)
  {
    return utf8_decode($value);
  }

  public function getNAMEAttribute($value)
  {
    return utf8_decode($value);
  }
}