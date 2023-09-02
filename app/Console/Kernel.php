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
    $queue_controller = new VacationQueueController();
    $schedule->call(function () use ($queue_controller, $request) {
      $queue_controller->queue_vacation($request);
    })->dailyAt('00:00');
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
