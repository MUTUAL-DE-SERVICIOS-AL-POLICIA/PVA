<?php

namespace App;

use App\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];

  public function fullName($style="uppercase", $order="name_first")
  {
    return Util::fullName($this, $style, $order);
  }

  public function insurance_company()
  {
    return $this->belongsTo(InsuranceCompany::class);
  }

  public function city_identity_card()
  {
    return $this->belongsTo(City::class, 'city_identity_card_id', 'id');
  }

  public function management_entity()
  {
    return $this->belongsTo(ManagementEntity::class);
  }

  public function city_birth()
  {
    return $this->belongsTo(City::class, 'city_birth_id', 'id');
  }
}
