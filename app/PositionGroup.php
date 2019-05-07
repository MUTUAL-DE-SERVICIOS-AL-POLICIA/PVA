<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PositionGroup extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  public $timestamps = true;
  public $guarded = ['id'];
  protected $fillable = ['name', 'shortened', 'active', 'company_address_id'];

  public function depends_from()
  {
    return $this->belongsToMany(PositionGroup::class, 'dependency_position_group', 'dependent_id', 'superior_id');
  }

  public function dependents()
  {
    return $this->belongsToMany(PositionGroup::class, 'dependency_position_group', 'superior_id', 'dependent_id');
  }

  public function company_address()
  {
    return $this->belongsTo(CompanyAddress::class);
  }

  public function positions()
  {
    return $this->hasMany(Position::class);
  }

  public function payrolls()
  {
    return $this->hasMany(Payroll::class);
  }

  public function locations()
  {
    return $this->hasMany(Location::class);
  }
}
