<?php

namespace App;

use Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssistantContract extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  public $timestamps = true;
  public $guarded = ['id'];
  protected $fillable = ['employee_id', 'university', 'start_date', 'end_date', 'assistant_position', 'position_group_id', 'retirement_date', 'retirement_reason_id', 'register_number', 'description', 'active'];

  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  public function retirement_reason()
  {
    return $this->belongsTo(RetirementReason::class);
  }

  public function job_schedules()
  {
    return $this->belongsToMany(JobSchedule::class, 'contract_job_schedule');
  }

  public function position_group()
  {
	return $this->belongsTo(PositionGroup::class, 'position_group_id');
  }
}
