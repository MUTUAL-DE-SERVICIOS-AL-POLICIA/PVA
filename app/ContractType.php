<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractType extends Model
{
  public $timestamps = true;
  public $guarded = ['id'];
  protected $fillable = ['ovt_id', 'name'];

  public function contract()
  {
    return $this->hasMany(Contract::class);
  }
}
