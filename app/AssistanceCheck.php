<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssistanceCheck extends Model
{
  protected $connection = 'assistance';
  protected $table = 'checkinout';
  public $timestamps = false;
  protected $fillable = [];
  protected $primaryKey = null;
  public $incrementing = false;

  public function user()
  {
    return $this->belongsTo(AssistanceUser::class, 'USERID', 'USERID');
  }
}
