<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MinimumSalary extends Model
{
  public $timestamps = false;
  public $guarded = ['id'];
  protected $fillable = ['value', 'year', 'active'];
}
