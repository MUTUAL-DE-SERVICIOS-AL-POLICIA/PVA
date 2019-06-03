<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssistanceUser extends Model
{
  protected $connection = 'assistance';
  protected $table = 'userinfo';
  public $timestamps = false;
  protected $fillable = [];
  protected $primaryKey = 'userid';

  public function checks()
  {
    return $this->hasMany(AssistanceCheck::class, 'USERID', 'USERID')->select('checktime');
  }
}
