<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubarticleRequest extends Model
{
  protected $connection = 'nsiaf';
  protected $table = 'subarticle_requests';

  public function supply_request()
  {
    return $this->belongsTo(SupplyRequest::class, 'request_id');
  }
}
