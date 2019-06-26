<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobScheduleDiscount extends Model
{
  public $timestamps = false;
  public $guarded = ['id'];
  protected $fillable = ['limit', 'time', 'unit', 'discount', 'active'];
}
