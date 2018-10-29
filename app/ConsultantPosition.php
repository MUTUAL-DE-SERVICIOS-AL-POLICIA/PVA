<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultantPosition extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  public $timestamps = true;
  public $guarded = ['id'];
  protected $fillable = ['name', 'charge_id', 'position_group_id', 'active'];

  public function depends_from()
  {
    return $this->belongsToMany(Position::class, 'dependency_positions', 'consultant_id', 'superior_id');
  }

  public function charge()
  {
    return $this->belongsTo(Charge::class);
  }

  public function position_group()
  {
    return $this->belongsTo(PositionGroup::class);
  }

  public function contracts()
  {
    return $this->hasMany(ConsultantContract::class);
  }

  public function payrolls()
  {
    return $this->hasMany(ConsultantPayroll::class);
  }
}
