<?php

namespace App\Observers;

use App\CasCertification;
use App\Helpers\Util;

class CasCertificationObserver
{
    /**
     * Handle the cas certification "created" event.
     *
     * @param  \App\CasCertification  $casCertification
     * @return void
     */
    public function created(CasCertification $casCertification)
    {
        Util::save_action('Registrado certificado CAS: ' . $this->get_title($casCertification));
    }

    /**
     * Handle the cas certification "updated" event.
     *
     * @param  \App\CasCertification  $casCertification
     * @return void
     */
    public function updated(CasCertification $casCertification)
    {
        $changesArray = $casCertification->getChanges();

        if (!empty($changesArray)) {
            $old = new CasCertification();
            $old->forceFill($casCertification->getOriginal());

            $changes = 'Cambio de datos del certificado CAS ' . $this->get_title($casCertification) . ':';

            foreach ($changesArray as $key => $newValue) {
                $oldValue = $old->getAttribute($key);

                if ($key === 'employee_id') {
                    $oldValue = optional($old->employee)->fullName();
                    $newValue = optional($casCertification->employee)->fullName();
                }

                if ($key === 'active') {
                    $oldValue = $oldValue ? 'Activo' : 'Inactivo';
                    $newValue = $newValue ? 'Activo' : 'Inactivo';
                }

                if ($key === 'issue_date') {
                    $oldValue = $oldValue ? date('Y-m-d', strtotime($oldValue)) : null;
                    $newValue = $newValue ? date('Y-m-d', strtotime($newValue)) : null;
                }

                // concatenar en el log
                $changes .= " [{$key}] {$oldValue} => {$newValue}";
            }

            Util::save_action($changes);
        }
    }
    /**
     * Handle the cas certification "deleted" event.
     *
     * @param  \App\CasCertification  $casCertification
     * @return void
     */
    public function deleted(CasCertification $casCertification)
    {
        Util::save_action('Eliminó certificado CAS: ' . $this->get_title($casCertification));
    }
    
    /**
     * Handle the cas certification "get_title" event.
     *
     * @param  \App\CasCertification  $casCertification
     * @return void
     */
    private function get_title(CasCertification $casCertification)
    {
        return (($casCertification->certification_number ? ($casCertification->certification_number . ' - ') : '') . ' del empleado ' . $casCertification->employee->fullName());
    }
}
