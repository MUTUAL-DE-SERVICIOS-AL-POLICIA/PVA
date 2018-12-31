<?php

namespace App\Observers;

use App\Contract;
use App\Helpers\Util;

class ContractObserver
{
  /**
   * Handle the contract "created" event.
   *
   * @param  \App\Contract  $contract
   * @return void
   */
  public function created(Contract $contract)
  {
    Util::save_action('Registrado contrato: ' . $this->get_title($contract));
  }

  /**
   * Handle the contract "updating" event.
   *
   * @param  \App\Contract  $contract
   * @return void
   */
  public function updating(Contract $changed)
  {
    if ($changed->isDirty()) {
      $old = new Contract();
      $old->fill($changed->getOriginal());
      $changes = 'Cambiados datos del contrato ' . $this->get_title($changed) . ':';
      foreach ($changed->getDirty() as $key => $value) {
        if ($key == 'employee_id') {
          $changes .= (' [' . $key . '] ' . $old->employee->fullName() . ' => ' . $changed->employee->fullName() . ',');
        } elseif ($key == 'position_id') {
          $changes .= (' [' . $key . '] ' . $old->position->name . ' => ' . $changed->position->name . ',');
        } elseif ($key == 'contract_type_id') {
          $changes .= (' [' . $key . '] ' . $old->contract_type->name . ' => ' . $changed->contract_type->name . ',');
        } elseif ($key == 'contract_mode_id') {
          $changes .= (' [' . $key . '] ' . $old->contract_mode->name . ' => ' . $changed->contract_mode->name . ',');
        } elseif ($key == 'retirement_reason_id') {
          $changes .= (' [' . $key . '] ' . $old->retirement_reason->name . ' => ' . $changed->retirement_reason->name . ',');
        } elseif ($key == 'insurance_company_id') {
          $changes .= (' [' . $key . '] ' . $old->insurance_company->name . ' => ' . $changed->insurance_company->name . ',');
        } else {
          $changes .= (' [' . $key . '] ' . $old[$key] . ' => ' . $value . ',');
        }
      }
      Util::save_action($changes);
    }
  }

  /**
   * Handle the employee "deleted" event.
   *
   * @param  \App\Employee  $employee
   * @return void
   */
  public function deleted(Contract $contract)
  {
    Util::save_action('Eliminado contrato: ' . $this->get_title($contract));
  }

  private function get_title($contract)
  {
    return (($contract->contract_number ? ($contract->contract_number . ' - ') : '') . $contract->position->name . ' del empleado ' . $contract->employee->fullName());
  }
}
