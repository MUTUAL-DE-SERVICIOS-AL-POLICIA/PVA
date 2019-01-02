<?php

namespace App\Observers;

use App\ConsultantPayroll;
use App\Helpers\Util;

class ConsultantPayrollObserver
{
  /**
   * Handle the consultant payroll "updated" event.
   *
   * @param  \App\ConsultantPayroll  $consultantPayroll
   * @return void
   */
  public function updating(ConsultantPayroll $changed)
  {
    $old = new ConsultantPayroll();
    $old->fill($changed->getOriginal());
    $changes = 'Cambiados datos de boleta ' . $this->get_title($changed) . ':';
    foreach ($changed->getDirty() as $key => $value) {
      if ($key == 'consultant_procedure_id') {
        $changes .= (' [' . $key . '] ' . $this->get_procedure_title($old->consultant_procedure) . ' => ' . $this->get_procedure_title($changed->consultant_procedure));
      } elseif ($key == 'consultant_contract_id') {
        $changes .= (' [' . $key . '] ' . $this->get_contract_title($old->consultant_contract) . ' => ' . $this->get_contract_title($changed->consultant_contract));
      } elseif ($key == 'employee_id') {
        $changes .= (' [' . $key . '] ' . $old->employee->fullName() . ' => ' . $changed->employee->fullName() . ',');
      } elseif ($key == 'charge_id') {
        $changes .= (' [' . $key . '] ' . $old->charge->name . ' => ' . $changed->charge->name . ',');
      } elseif ($key == 'position_group_id') {
        $changes .= (' [' . $key . '] ' . $old->position_group->name . ' => ' . $changed->position_group->name . ',');
      } elseif ($key == 'consultant_position_id') {
        $changes .= (' [' . $key . '] ' . $old->consultant_position->name . ' => ' . $changed->consultant_position->name . ',');
      } else {
        $changes .= (' [' . $key . '] ' . $old[$key] . ' => ' . $value . ',');
      }
    }
    Util::save_action($changes);
  }

  /**
   * Handle the consultant payroll "deleted" event.
   *
   * @param  \App\ConsultantPayroll  $consultantPayroll
   * @return void
   */
  public function deleted(ConsultantPayroll $payroll)
  {
    Util::save_action('Eliminada boleta de consultor: ' . $this->get_title($payroll));
  }

  private function get_title($payroll)
  {
    return ($payroll->code . ' [' . $payroll->id . ']');
  }

  private function get_contract_title($contract)
  {
    return (($contract->contract_number ? ($contract->contract_number . ' - ') : '') . $contract->consultant_position->name . ' del consultor ' . $contract->employee->fullName());
  }

  private function get_procedure_title($procedure)
  {
    return $procedure->month->name . '-' . $procedure->year;
  }
}
