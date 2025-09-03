<?php

namespace App;

use Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultantContract extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  public $timestamps = true;
  public $guarded = ['id'];
  protected $fillable = ['employee_id', 'consultant_position_id', 'start_date', 'end_date', 'retirement_date', 'retirement_reason_id', 'active', 'rrhh_cite', 'rrhh_cite_date', 'contract_number', 'description'];

  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  public function consultant_position()
  {
    return $this->belongsTo(ConsultantPosition::class);
  }

  public function retirement_reason()
  {
    return $this->belongsTo(RetirementReason::class);
  }

  public function job_schedules()
  {
    return $this->belongsTo(JobSchedule::class, 'job_schedule_id');
  }

  public function payrolls()
  {
    return $this->hasMany(ConsultantPayroll::class);
  }

  public function valid_date($year, $month)
  {
    $first_day_of_month = Carbon::create($year, $month, 1)->format('Y-m-d');
    $last_day_of_month = Carbon::create($year, $month, 1)->endOfMonth()->format('Y-m-d');

    return ConsultantContract::where(function ($query) use ($year, $month, $last_day_of_month, $first_day_of_month) {
      $query
        ->orWhere(function ($q) use ($last_day_of_month) {
          $q
            ->whereNull('retirement_date')
            ->whereNull('end_date')
            ->whereDate('start_date', '<=', $last_day_of_month);
        })
        ->orWhere(function ($q) use ($first_day_of_month, $last_day_of_month) {
          $q
            ->whereNotNull('retirement_date')
            ->whereDate('retirement_date', '>=', $first_day_of_month)
            ->whereDate('start_date', '<=', $last_day_of_month);
        })
        ->orWhere(function ($q) use ($year, $month) {
          $q
            ->whereNull('retirement_date')
            ->whereYear('end_date', $year)
            ->whereMonth('end_date', $month);
        })
        ->orWhere(function ($q) use ($year, $month) {
          $q
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month);
        })
        ->orWhere(function ($q) use ($first_day_of_month, $last_day_of_month) {
          $q
            ->whereNull('retirement_date')
            ->whereDate('end_date', '>=', $first_day_of_month)
            ->whereDate('start_date', '<=', $last_day_of_month);
        });
    })->leftjoin('employees as e', 'e.id', '=', 'consultant_contracts.employee_id')->select('consultant_contracts.*')->orderBy('e.last_name')->orderBy('e.mothers_last_name')->orderBy('consultant_contracts.start_date')->get();
  }
}
