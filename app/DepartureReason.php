<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartureReason extends Model
{
  public $timestamps  = true;
  public $guarded     = ['id'];
  protected $fillable = ['name', 'departure_group_id', 'name', 'note', 'day', 'hour', 'each', 'pay', 'count', 'description'];

  public function departure_group()
  {
    return $this->belongsTo(DepartureGroup::class);
  }
}
