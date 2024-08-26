<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartureReasonVacationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Personal
        $group = App\DepartureGroup::where('name', 'PERSONAL')->first();
        
        $newDepartureReason = new App\DepartureReason([
            'name' => 'VACACIONES',
            'days' => null,
            'hours' => null,
            'reset' => null,
            'payable' => true,
            'note' => true,
            'description_needed' => false,
            'description' => 'Asignación de vacaciones programadas o a cuenta de vacación.',
        ]);
        
        $group->departure_reasons()->save($newDepartureReason);
    }
}
