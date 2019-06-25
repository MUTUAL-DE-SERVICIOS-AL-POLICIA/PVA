<?php

use Illuminate\Database\Seeder;

class JobScheduleMinMaxSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $job_schedule = App\JobSchedule::whereStartHour(8)->whereStartMinutes(0)->whereEndHour(12)->whereEndMinutes(0)->first();
    if ($job_schedule) {
      $job_schedule->start_hour_min_limit = 6;
      $job_schedule->start_minutes_min_limit = 0;
      $job_schedule->end_hour_max_limit = 13;
      $job_schedule->end_minutes_max_limit = 0;
      $job_schedule->save();
    }

    $job_schedule = App\JobSchedule::whereStartHour(14)->whereStartMinutes(30)->whereEndHour(18)->whereEndMinutes(30)->first();
    if ($job_schedule) {
      $job_schedule->start_hour_min_limit = 13;
      $job_schedule->start_minutes_min_limit = 30;
      $job_schedule->end_hour_max_limit = 23;
      $job_schedule->end_minutes_max_limit = 59;
      $job_schedule->save();
    }

    $job_schedule = App\JobSchedule::whereStartHour(7)->whereStartMinutes(0)->whereEndHour(15)->whereEndMinutes(0)->first();
    if ($job_schedule) {
      $job_schedule->start_hour_min_limit = 5;
      $job_schedule->start_minutes_min_limit = 0;
      $job_schedule->end_hour_max_limit = 23;
      $job_schedule->end_minutes_max_limit = 59;
      $job_schedule->save();
    }

    $job_schedule = App\JobSchedule::whereStartHour(15)->whereStartMinutes(0)->whereEndHour(23)->whereEndMinutes(0)->first();
    if ($job_schedule) {
      $job_schedule->start_hour_min_limit = 13;
      $job_schedule->start_minutes_min_limit = 0;
      $job_schedule->end_hour_max_limit = 23;
      $job_schedule->end_minutes_max_limit = 59;
      $job_schedule->save();
    }

    $job_schedule = App\JobSchedule::whereStartHour(23)->whereStartMinutes(0)->whereEndHour(7)->whereEndMinutes(0)->first();
    if ($job_schedule) {
      $job_schedule->start_hour_min_limit = 21;
      $job_schedule->start_minutes_min_limit = 0;
      $job_schedule->end_hour_max_limit = 9;
      $job_schedule->end_minutes_max_limit = 0;
      $job_schedule->save();
    }
  }
}
