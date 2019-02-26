<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MinimumSalary extends Model
{
  public $timestamps = true;
  public $guarded = ['id'];
  protected $fillable = ['value'];

  public function procedures()
  {
    return $this->hasMany(Procedure::class);
  }
}
