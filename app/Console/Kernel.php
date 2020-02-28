<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Api\V1\AttendanceController;
use Illuminate\Http\Request;

class Kernel extends ConsoleKernel
{
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    //
  ];

  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    $request = Request::create(null, null, []);
    $attendance_controller = new AttendanceController();
    $schedule->call(function () use ($attendance_controller, $request) {
        $attendance_controller->store($request);
    })->daily();
    $schedule->call(function () use ($attendance_controller) {
      do {
        for ($i = 1; $i <= 3; $i++) {
          $response = $attendance_controller->store($request);
          sleep(30*60);
        }
      } while (count($response->getData()->added) > 0);
      $attendance_controller->destroy('all');
    })->weekly()->sundays()->at('01:00');
  }

  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected function commands()
  {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }
}
