<?php

namespace App\Observers;

use App\ConsultantProcedure;
use App\Helpers\Util;

class ConsultantProcedureObserver
{
  /**
   * Handle the consultant procedure "created" event.
   *
   * @param  \App\ConsultantProcedure  $consultantProcedure
   * @return void
   */
  public function created(ConsultantProcedure $procedure)
  {
    Util::save_action('Registrada planilla de consultores: ' . $this->get_title($procedure));
  }

  /**
   * Handle the consultant procedure "updated" event.
   *
   * @param  \App\ConsultantProcedure  $consultantProcedure
   * @return void
   */
  public function updating(ConsultantProcedure $changed)
  {
    if ($changed->isDirty()) {
      $old = new ConsultantProcedure();
      $old->fill($changed->getOriginal());
      $changes = 'Cambiados datos de planilla de consultores' . $this->get_title($changed) . ':';
      foreach ($changed->getDirty() as $key => $value) {
        if ($key == 'month_id') {
          $changes .= (' [' . $key . '] ' . $old->month->name . ' => ' . $changed->month->name . ',');
        } elseif ($key == 'active') {
          $changes .= (' [' . $key . '] ' . json_encode($old->active) . ' => ' . json_encode($value) . ',');
        } else {
          $changes .= (' [' . $key . '] ' . $old[$key] . ' => ' . $value . ',');
        }
      }
      Util::save_action($changes);
    }
  }

  /**
   * Handle the consultant procedure "deleted" event.
   *
   * @param  \App\ConsultantProcedure  $consultantProcedure
   * @return void
   */
  public function deleted(ConsultantProcedure $procedure)
  {
    Util::save_action('Eliminada planilla de consultores: ' . $this->get_title($procedure));
  }

  private function get_title($procedure)
  {
    return ($procedure->month->name . '-' . $procedure->year);
  }
}
