<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonusYear extends Model
{
  protected $primaryKey = 'year';
  public $timestamps = true;
  protected $fillable = ['year', 'bonus'];

  public function procedures()
  {
    return $this->hasMany(BonusProcedure::class, 'year');
  }
}
