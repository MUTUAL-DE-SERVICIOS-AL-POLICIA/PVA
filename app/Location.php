<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
  use SoftDeletes;
  public $timestamps = true;
  public $guarded = ['id'];
  protected $dates = ['deleted_at'];
  protected $fillable = ['name', 'phone_number', 'position_group_id'];

  public function position_group()
  {
    return $this->belongsTo(PositionGroup::class);
  }
}
