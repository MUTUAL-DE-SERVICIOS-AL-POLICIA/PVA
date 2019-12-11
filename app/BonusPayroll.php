<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonusPayroll extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  public $timestamps = true;
  public $guarded = ['id'];
  protected $fillable = ['bonus_procedure_id', 'contract_id', 'start_date', 'end_date', 'months', 'days', 'wages', 'account_number'];
  protected $casts = ['wages' => 'array'];

  public function contract()
  {
    return $this->belongsTo(Contract::class);
  }

  public function bonus_procedure()
  {
    return $this->belongsTo(BonusProcedure::class);
  }


}