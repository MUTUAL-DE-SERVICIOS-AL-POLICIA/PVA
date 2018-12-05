<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultantProcedure extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  public $timestamps = true;
  public $guarded = ['id'];
  protected $fillable = ['year', 'month_id', 'active', 'pay_date'];

  public function month()
  {
    return $this->belongsTo(Month::class);
  }

  public function payrolls()
  {
    return $this->hasMany(ConsultantPayroll::class);
  }
}
