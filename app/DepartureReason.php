<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartureReason extends Model
{
  public $timestamps  = true;
  public $guarded     = ['id'];
  protected $fillable = ['name', 'days', 'hours', 'reset', 'payable', 'description', 'departure_group_id', 'note', 'description_needed', 'count'];

  public function departure_group()
  {
    return $this->belongsTo(DepartureGroup::class);
  }
}
