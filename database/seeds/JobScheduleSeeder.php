<?php

use Illuminate\Database\Seeder;

class JobScheduleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $schedules = [
      ['start_hour' => 8, 'start_minutes' => 0, 'end_hour' => 12, 'end_minutes' => 0,],
      ['start_hour' => 14, 'start_minutes' => 30, 'end_hour' => 18, 'end_minutes' => 30,],
      ['start_hour' => 7, 'start_minutes' => 0, 'end_hour' => 15, 'end_minutes' => 0,],
      ['start_hour' => 15, 'start_minutes' => 0, 'end_hour' => 23, 'end_minutes' => 0,],
      ['start_hour' => 23, 'start_minutes' => 0, 'end_hour' => 7, 'end_minutes' => 0,],
    ];

    foreach ($schedules as $schedule) {
      App\JobSchedule::create($schedule);
    }
  }
}
