<?php

namespace App\Observers;

use App\Procedure;
use App\Helpers\Util;

class ProcedureObserver
{
  /**
   * Handle the procedure "created" event.
   *
   * @param  \App\Procedure  $procedure
   * @return void
   */
  public function created(Procedure $procedure)
  {
    Util::save_action('Registrada planilla: ' . $this->get_title($procedure));
  }

  /**
   * Handle the procedure "updated" event.
   *
   * @param  \App\Procedure  $procedure
   * @return void
   */
  public function updating(Procedure $changed)
  {
    if ($changed->isDirty()) {
      $old = new Procedure();
      $old->fill($changed->getOriginal());
      $changes = 'Cambiados datos de planilla ' . $this->get_title($changed) . ':';
      foreach ($changed->getDirty() as $key => $value) {
        if ($key == 'month_id') {
          $changes .= (' [' . $key . '] ' . $old->month->name . ' => ' . $changed->month->name . ',');
        } elseif ($key == 'employee_discount_id') {
          $changes .= (' [' . $key . '] ' . $old->employee_discount->toArray() . ' => ' . $changed->employee_discount->toArray() . ',');
        } elseif ($key == 'employer_contribution_id') {
          $changes .= (' [' . $key . '] ' . $old->employer_contribution->toArray() . ' => ' . $changed->employer_contribution->toArray() . ',');
        } elseif ($key == 'minimum_salary_id') {
          $changes .= (' [' . $key . '] ' . $old->minimum_salary->toArray() . ' => ' . $changed->minimum_salary->toArray() . ',');
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
   * Handle the procedure "deleted" event.
   *
   * @param  \App\Procedure  $procedure
   * @return void
   */
  public function deleted(Procedure $procedure)
  {
    Util::save_action('Eliminada planilla: ' . $this->get_title($procedure));
  }

  private function get_title($procedure)
  {
    return ($procedure->month->name . '-' . $procedure->year);
  }
}
