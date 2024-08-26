<?php

namespace App\Observers;

use App\Payroll;
use App\Helpers\Util;

class PayrollObserver
{
  /**
   * Handle the payroll "updated" event.
   *
   * @param  \App\Payroll  $payroll
   * @return void
   */
  public function updating(Payroll $changed)
  {
    if ($changed->isDirty()) {
      $old = new Payroll();
      $old->fill($changed->getOriginal());
      $changes = 'Cambiados datos de boleta ' . $this->get_title($changed) . ':';
      foreach ($changed->getDirty() as $key => $value) {
        if ($key == 'procedure_id') {
          $changes .= (' [' . $key . '] ' . $old->procedure->month->name . '-' . $old->procedure->year . ' => ' . $changed->procedure->month->name . '-' . $changed->procedure->year . ',');
        } elseif ($key == 'contract_id') {
          $changes .= (' [' . $key . '] ' . $this->get_contract_title($old->contract) . ' => ' . $this->get_contract_title($changed->contract));
        } elseif ($key == 'employee_id') {
          $changes .= (' [' . $key . '] ' . $old->employee->fullName() . ' => ' . $changed->employee->fullName() . ',');
        } elseif ($key == 'charge_id') {
          $changes .= (' [' . $key . '] ' . $old->charge->name . ' => ' . $changed->charge->name . ',');
        } elseif ($key == 'position_group_id') {
          $changes .= (' [' . $key . '] ' . $old->position_group->name . ' => ' . $changed->position_group->name . ',');
        } elseif ($key == 'position_id') {
          $changes .= (' [' . $key . '] ' . $old->position->name . ' => ' . $changed->position->name . ',');
        } else {
          $changes .= (' [' . $key . '] ' . $old[$key] . ' => ' . $value . ',');
        }
      }
      Util::save_action($changes);
    }
  }

  /**
   * Handle the payroll "deleted" event.
   *
   * @param  \App\Payroll  $payroll
   * @return void
   */
  public function deleted(Payroll $payroll)
  {
    Util::save_action('Eliminada boleta: ' . $this->get_title($payroll));
  }

  private function get_title($payroll)
  {
    return ($payroll->code . ' [' . $payroll->id . ']');
  }

  private function get_contract_title($contract)
  {
    return (($contract->contract_number ? ($contract->contract_number . ' - ') : '') . $contract->position->name . ' del empleado ' . $contract->employee->fullName());
  }
}
