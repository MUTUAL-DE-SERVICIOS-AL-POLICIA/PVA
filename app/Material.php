<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
  protected $connection = 'nsiaf';

  public function subarticles()
  {
    return $this->hasMany(Subarticle::class);
  }
}
