<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonusProcedure extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  public $timestamps = true;
  public $guarded = ['id'];
  protected $fillable = ['year', 'pay_date', 'name', 'split_percentage', 'limit_wage'];

  public function bonus_year()
  {
    return $this->belongsTo(BonusYear::class, 'year');
  }
}
