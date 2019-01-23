<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplyUser extends Model
{
  protected $connection = 'nsiaf';
  protected $table = 'users';

  public function requests()
  {
    return $this->hasMany(SupplyRequest::class, 'user_id');
  }

  public function department()
  {
    return $this->belongsTo(SupplyDepartment::class, 'department_id');
  }
}
