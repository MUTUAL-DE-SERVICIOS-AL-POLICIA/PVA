<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractType extends Model
{
  public function contracts()
  {
    return $this->hasMany(Contract::class);
  }
}
