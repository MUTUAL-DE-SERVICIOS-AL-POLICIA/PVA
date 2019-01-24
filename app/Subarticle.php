<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subarticle extends Model
{
  protected $connection = 'nsiaf';
  protected $fillable = ['id', 'code', 'description', 'unit', 'status', 'article_id', 'created_at', 'updated_at', 'amount', 'minimum', 'barcode', 'code_old', 'incremento', 'material_id', 'stock'];

  public function material()
  {
    return $this->belongsTo(Material::class);
  }

  public function requests()
  {
    return $this->belongsToMany(SupplyRequest::class, 'subarticle_requests', 'subarticle_id', 'request_id')->withPivot('id', 'amount', 'amount_delivered', 'total_delivered', 'invalidate');
  }

  public function entries()
  {
    return $this->hasMany(SubarticleEntry::class);
  }

  public function stock()
  {
    $entries = $this->entries()->where('invalidate', 0)->sum('amount');

    $requests = $this->requests()->wherePivot('invalidate', 0)->where('status', 'delivered')->where('requests.invalidate', 0)->sum('total_delivered');

    $this->stock = $entries - $requests;

    return $this;
  }
}