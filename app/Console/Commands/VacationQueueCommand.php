<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VacationQueueCommand extends Command
{
    protected $signature = 'vacation:queue';
    protected $description = 'Procesa la cola de vacaciones';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info('Scheduler vacation:queue ejecutado');
        $controller = app(\App\Http\Controllers\Api\V1\VacationQueueController::class);
        $controller->queue_vacation();
    }
}
