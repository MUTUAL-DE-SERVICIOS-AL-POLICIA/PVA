<?php

namespace App\Observers;

use App\Helpers\Util;
use App\vacationQueue;

class VacationQueueObserver
{
    /**
     * Handle the vacation queue "created" event.
     *
     * @param  \App\vacationQueue  $vacationQueue
     * @return void
     */
    public function created(vacationQueue $vacationQueue)
    {
        Util::save_action('Registró asignación individual de vacacion Nro [' . $vacationQueue->id . '] de '. $this->get_title($vacationQueue));
    }

    /**
     * Handle the vacation queue "updated" event.
     *
     * @param  \App\vacationQueue  $vacationQueue
     * @return void
     */
    public function updated(vacationQueue $vacationQueue)
    {
        //
    }

    /**
     * Handle the vacation queue "deleted" event.
     *
     * @param  \App\vacationQueue  $vacationQueue
     * @return void
     */
    public function deleted(vacationQueue $vacationQueue)
    {
        Util::save_action('Eliminó asignación individual de vacacion Nro [' . $vacationQueue->id . '] de '. $this->get_title($vacationQueue));
    }

    /**
     * Handle the vacation queue "restored" event.
     *
     * @param  \App\vacationQueue  $vacationQueue
     * @return void
     */
    public function restored(vacationQueue $vacationQueue)
    {
        //
    }

    /**
     * Handle the vacation queue "force deleted" event.
     *
     * @param  \App\vacationQueue  $vacationQueue
     * @return void
     */
    public function forceDeleted(vacationQueue $vacationQueue)
    {
        //
    }

    private function get_title($vacationQueue)
    {
        return ($vacationQueue->employee->fullName());
    }
}
