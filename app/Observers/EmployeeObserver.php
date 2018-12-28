<?php

namespace App\Observers;

use App\Employee;
use App\City;
use App\ManagementEntity;
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
    Util::save_action('Registrado empleado: ' . Util::fullName($employee));
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
      $old = $changed->getOriginal();
      $changes = 'Cambiados datos de empleado ' . Util::fullName((object)$old) . ' :';
      foreach ($changed->getDirty() as $key => $value) {
        if ($key == 'city_identity_card_id' || $key == 'city_birth_id') {
          $changes .= (' [' . $key . '] ' . City::find($old[$key])->name . ' => ' . City::find($value)->name . ',');
        } elseif ($key == 'management_entity_id') {
          $changes .= (' [' . $key . '] ' . ManagementEntity::find($old[$key])->name . ' => ' . ManagementEntity::find($value)->name . ',');
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
    Util::save_action('Eliminado empleado: ' . Util::fullName($employee));
  }
}
