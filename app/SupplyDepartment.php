<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplyDepartment extends Model
{
  protected $connection = 'nsiaf';
  protected $table = 'departments';

  public function users()
  {
    return $this->hasMany(SupplyUser::class, 'department_id', 'id');
  }
}
