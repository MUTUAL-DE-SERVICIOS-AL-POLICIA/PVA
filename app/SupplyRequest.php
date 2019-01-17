<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplyRequest extends Model
{
  protected $connection = 'nsiaf';
  protected $table = 'requests';

  public function subarticle_requests()
  {
    return $this->hasMany(SubarticleRequest::class, 'request_id', 'id');
  }
}
