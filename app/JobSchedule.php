<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;
use Util;

class JobSchedule extends Model
{
  public $timestamps = true;
  public $guarded = ['id'];
  protected $fillable = ['start_hour', 'start_minutes', 'end_hour', 'end_minutes', 'workdays', 'start_hour_min_limit', 'start_minutes_min_limit', 'end_hour_max_limit', 'end_minutes_max_limit'];
  protected $casts = ['workdays' => 'array'];

  public function contracts()
  {
      return $this->hasMany(Contract::class, 'job_schedule_id');
  }

  public function consultant_contracts()
  {
      return $this->hasMany(ConsultantContract::class, 'job_schedule_id');
  }

  public function assistant_contracts()
  {
      return $this->hasMany(AssistantContract::class, 'job_schedule_id');
  }

  public function iso_format()
  {
    $this->start = Util::fillZerosLeft($this->start_hour, 2) . ':' . Util::fillZerosLeft($this->start_minutes, 2);
    $this->end = Util::fillZerosLeft($this->end_hour, 2) . ':' . Util::fillZerosLeft($this->end_minutes, 2);
    $this->start_limit = Util::fillZerosLeft($this->start_hour_min_limit, 2) . ':' . Util::fillZerosLeft($this->start_minutes_min_limit, 2);
    $this->end_limit = Util::fillZerosLeft($this->end_hour_max_limit, 2) . ':' . Util::fillZerosLeft($this->end_minutes_max_limit, 2);
    $this->days = $this->iso_workdays();
    foreach ($this->getFillable() as $key) {
      unset($this[$key]);
    }
  }

  public function iso_workdays()
  {
    $day = Carbon::now()->startOfWeek();
    $days = [];
    for ($i = 1; $i <= 7; $i++) {
      array_push($days, (object)[
        'weekday' => $day->dayOfWeekIso,
        'name' => $day->ISOFormat('dddd'),
        'value' => in_array($day->dayOfWeekIso, $this->workdays)
      ]);
      $day->addDay();
    }
    return $days;
  }
}
