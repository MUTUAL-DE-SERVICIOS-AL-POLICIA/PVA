<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubarticleEntry extends Model
{
  protected $connection = 'nsiaf';
  protected $table = 'entry_subarticles';

  public function subarticle()
  {
    return $this->belongsTo(Subarticle::class);
  }
}
