<?php

namespace App;

use Carbon;

class EmployeeDeparture
{
  public function __construct($departure)
  {
    $range = [];
    $departure_minutes = 0;
    $diffMinutes = 0;
    $departure_date = Carbon::parse($departure->departure_date);
    $return_date    = Carbon::parse($departure->return_date);
    $diffdays       = $return_date->diffInDays($departure_date);

    for ($i = 0; $i <= $diffdays; $i++) {
      $break = false;
      for ($j = 1; $j <= 2; $j++) {
        $time                                   = $this->days_hours($departure, $diffdays, $i, $j, $break);
        $departure_schedule['id']               = $departure->id;
        $departure_schedule['departure_group']   = $departure->departure_reason->departure_group->name;
        $departure_schedule['departure_reason'] = $departure->departure_reason->name;
        $departure_schedule['departure_date']   = $departure_date->format('Y-m-d');
        $departure_schedule['departure_time']   = $time[0];
        $departure_schedule['return_time']      = $time[1];

        $h1 = Carbon::parse($time[0]);
        $h2 = Carbon::parse($time[1]);
        $diffMinutes = $h2->diffInMinutes($h1);

        if ($departure_date->isWeekday()) {
          $range[] = $departure_schedule;
          $departure_minutes = $departure_minutes + $diffMinutes;
        }
        if ($time[2] == true) {
          break;
        }
      }
      $departure_date = $departure_date->addDay();
    }

    $this->departure_schedule = $range;
    $this->departure_minutes = $departure_minutes;
    return $this;
  }

  private function days_hours($departure, $diffdays, $i, $j, $break)
  {
    $start_time = Carbon::parse($departure->contract->job_schedules[0]->start_hour . ':' . $departure->contract->job_schedules[0]->start_minutes)->format('H:i:s');
    $end_time = Carbon::parse($departure->contract->job_schedules[0]->end_hour . ':' . $departure->contract->job_schedules[0]->end_minutes)->format('H:i:s');

    if (isset($departure->contract->job_schedules[1])) {
      $start_time2 = Carbon::parse($departure->contract->job_schedules[1]->start_hour . ':' . $departure->contract->job_schedules[1]->start_minutes)->format('H:i:s');
      $end_time2 = Carbon::parse($departure->contract->job_schedules[1]->end_hour . ':' . $departure->contract->job_schedules[1]->end_minutes)->format('H:i:s');

      if ($diffdays == 0) {
        if (strtotime($departure->departure_time) <= strtotime($end_time) && strtotime($departure->return_time) <= strtotime($start_time2)) {
          $time1 = $departure->departure_time;
          $time2 = $departure->return_time;
          $break = true;
        } elseif (strtotime($departure->departure_time) >= strtotime($start_time2) && strtotime($departure->return_time) >= strtotime($start_time2)) {
          $time1 = $departure->departure_time;
          $time2 = $departure->return_time;
          $break = true;
        } else {
          if ($j == 1) {
            $time1 = $departure->departure_time;
            $time2 = $end_time;
          } else {
            $time1 = $start_time2;
            $time2 = $departure->return_time;
          }
        }
      } else {
        if ($i == 0) {
          if (strtotime($departure->departure_time) >= strtotime($start_time2)) {
            $time1 = $departure->departure_time;
            $time2 = $end_time2;
            $break = true;
          } else {
            if ($j == 1) {
              $time1 = $departure->departure_time;
              $time2 = $end_time;
            } else {
              $time1 = $start_time2;
              $time2 = $end_time2;
            }
          }
        } elseif ($i == $diffdays) {
          if (strtotime($departure->return_time) <= strtotime($start_time2)) {
            $time1 = $start_time;
            $time2 = $departure->return_time;
            $break = true;
          } else {
            if ($j == 1) {
              $time1 = $start_time;
              $time2 = $end_time;
            } else {
              $time1 = $start_time2;
              $time2 = $departure->return_time;
            }
          }
        } else {
          if ($j == 1) {
            $time1 = $start_time;
            $time2 = $end_time;
          } else {
            $time1 = $start_time2;
            $time2 = $end_time2;
          }
        }
      }
    } else {
      if ($diffdays == 0) {
        $time1 = $departure->departure_time;
        $time2 = $departure->return_time;
        $break = true;
      } else {
        if ($i == 0) {
          $time1 = $departure->departure_time;
          $time2 = $end_time;
          $break = true;
        } elseif ($i == $diffdays) {
          $time1 = $start_time;
          $time2 = $departure->return_time;
          $break = true;
        } else {
          $time1 = $start_time;
          $time2 = $end_time;
          $break = true;
        }
      }
    }
    return [$time1, $time2, $break];
  }
}
