<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplyRequest extends Model
{
  protected $connection = 'nsiaf';
  protected $table = 'requests';
  public $timestamps = true;

  public function subarticles()
  {
    return $this->belongsToMany(Subarticle::class, 'subarticle_requests', 'request_id', 'subarticle_id')->withPivot('id', 'amount', 'amount_delivered', 'total_delivered', 'invalidate');
  }
}
