<?php

namespace App\Observers;

use App\ConsultantContract;
use App\Helpers\Util;

class ConsultantContractObserver
{
  /**
   * Handle the consultant contract "created" event.
   *
   * @param  \App\ConsultantContract  $consultantContract
   * @return void
   */
  public function created(ConsultantContract $contract)
  {
    Util::save_action('Registrado contrato: ' . $this->get_title($contract));
  }

  /**
   * Handle the consultant contract "updated" event.
   *
   * @param  \App\ConsultantContract  $consultantContract
   * @return void
   */
  public function updating(ConsultantContract $changed)
  {
    if ($changed->isDirty()) {
      $old = new ConsultantContract();
      $old->fill($changed->getOriginal());
      $changes = 'Cambiados datos del contrato ' . $this->get_title($changed) . ':';
      foreach ($changed->getDirty() as $key => $value) {
        if ($key == 'employee_id') {
          $changes .= (' [' . $key . '] ' . $old->employee->fullName() . ' => ' . $changed->employee->fullName() . ',');
        } elseif ($key == 'consultant_position_id') {
          $changes .= (' [' . $key . '] ' . $this->get_position_title($old->consultant_position) . ' => ' . $this->get_position_title($changed->consultant_position) . ',');
        } elseif ($key == 'retirement_reason_id') {
          $changes .= (' [' . $key . '] ' . ' => ' . $changed->retirement_reason->name . ',');
        } else {
          $changes .= (' [' . $key . '] ' . $old[$key] . ' => ' . $value . ',');
        }
      }
      Util::save_action($changes);
    }
  }

  /**
   * Handle the consultant contract "deleted" event.
   *
   * @param  \App\ConsultantContract  $consultantContract
   * @return void
   */
  public function deleted(ConsultantContract $contract)
  {
    Util::save_action('Eliminado contrato: ' . $this->get_title($contract));
  }

  private function get_title($contract)
  {
    return (($contract->contract_number ? ($contract->contract_number . ' - ') : '') . $contract->consultant_position->name . ' del consultor ' . $contract->employee->fullName());
  }

  private function get_position_title($position)
  {
    return $position->position_group->name . ' [' . $position->charge->base_wage . ' Bs.]';
  }
}
