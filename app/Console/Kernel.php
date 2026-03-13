<?php
namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\MyMinuteTask;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\VacationQueueController;
use Illuminate\Http\Request;
class Kernel extends ConsoleKernel
{
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    \App\Console\Commands\VacationQueueCommand::class,
  ];
  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
      $schedule->command('vacation:queue')->daily();
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
