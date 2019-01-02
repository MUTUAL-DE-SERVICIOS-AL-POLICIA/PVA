<?php

namespace App\Observers;

use App\ConsultantPosition;
use App\Helpers\Util;

class ConsultantPositionObserver
{
  /**
   * Handle the consultant position "created" event.
   *
   * @param  \App\ConsultantPosition  $consultantPosition
   * @return void
   */
  public function created(ConsultantPosition $position)
  {
    Util::save_action('Registrada consultoría: ' . $position->name);
  }

  /**
   * Handle the consultant position "updated" event.
   *
   * @param  \App\ConsultantPosition  $consultantPosition
   * @return void
   */
  public function updating(ConsultantPosition $changed)
  {
    if ($changed->isDirty()) {
      $old = new ConsultantPosition();
      $old->fill($changed->getOriginal());
      $changes = 'Cambiados datos de la consultoría ' . $this->get_title($changed) . ':';
      foreach ($changed->getDirty() as $key => $value) {
        if ($key == 'charge_id') {
          $changes .= (' [' . $key . '] ' . $this->get_charge_title($old->charge) . ' => ' . $this->get_charge_title($changed->charge) . ',');
        } elseif ($key == 'position_group_id') {
          $changes .= (' [' . $key . '] ' . $old->position_group->name . ' => ' . $changed->position_group->name . ',');
        } else {
          $changes .= (' [' . $key . '] ' . $old[$key] . ' => ' . $value . ',');
        }
      }
      Util::save_action($changes);
    }
  }

  /**
   * Handle the consultant position "deleted" event.
   *
   * @param  \App\ConsultantPosition  $consultantPosition
   * @return void
   */
  public function deleted(ConsultantPosition $position)
  {
    Util::save_action('Eliminada consultoría: ' . $position->name);
  }

  private function get_charge_title($charge)
  {
    return $charge->name . ' [' . $charge->base_wage . ' Bs.]';
  }
}
