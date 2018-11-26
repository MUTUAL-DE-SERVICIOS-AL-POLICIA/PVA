<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonusYear extends Model
{
  protected $primaryKey = 'year';
  public $timestamps = true;
  protected $fillable = ['bonus'];

  public function contracts()
  {
    return $this->hasMany(BonusProcedure::class);
  }
}
