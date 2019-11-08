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
}