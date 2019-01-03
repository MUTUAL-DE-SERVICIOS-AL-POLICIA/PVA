<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultantPayroll extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  public $timestamps = true;
  public $guarded = ['id'];
  protected $fillable = ['code', 'unworked_days', 'consultant_procedure_id', 'consultant_contract_id', 'employee_id', 'charge_id', 'position_group_id', 'consultant_position_id', 'faults'];

  public function consultant_procedure()
  {
    return $this->belongsTo(ConsultantProcedure::class);
  }

  public function consultant_contract()
  {
    return $this->belongsTo(ConsultantContract::class);
  }

  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  public function charge()
  {
    return $this->belongsTo(Charge::class);
  }

  public function position_group()
  {
    return $this->belongsTo(PositionGroup::class);
  }

  public function consultant_position()
  {
    return $this->belongsTo(ConsultantPosition::class);
  }
}
