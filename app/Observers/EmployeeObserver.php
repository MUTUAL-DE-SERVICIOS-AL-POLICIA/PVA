<?php

namespace App\Observers;

use App\Employee;
use App\Helpers\Util;

class EmployeeObserver
{
  /**
   * Handle the employee "created" event.
   *
   * @param  \App\Employee  $employee
   * @return void
   */
  public function created(Employee $employee)
  {
    Util::save_action('Registrado empleado: ' . $employee->fullName());
  }

  /**
   * Handle the employee "updating" event.
   *
   * @param  \App\Employee  $employee
   * @return void
   */
  public function updating(Employee $changed)
  {
    if ($changed->isDirty()) {
      $old = new Employee();
      $old->fill($changed->getOriginal());
      $changes = 'Cambiados datos de empleado ' . $changed->fullName() . ' :';
      foreach ($changed->getDirty() as $key => $value) {
        if ($key == 'city_identity_card_id') {
          $changes .= (' [' . $key . '] ' . $old->city_identity_card->name . ' => ' . $changed->city_identity_card->name . ',');
        } elseif ($key == 'city_birth_id') {
          $changes .= (' [' . $key . '] ' . $old->city_birth->name . ' => ' . $changed->city_birth->name . ',');
        } elseif ($key == 'management_entity_id') {
          $changes .= (' [' . $key . '] ' . $old->management_entity->name . ' => ' . $changed->management_entity->name . ',');
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
   * Handle the employee "deleted" event.
   *
   * @param  \App\Employee  $employee
   * @return void
   */
  public function deleted(Employee $employee)
  {
    Util::save_action('Eliminado empleado: ' . $employee->fullName());
  }
}
