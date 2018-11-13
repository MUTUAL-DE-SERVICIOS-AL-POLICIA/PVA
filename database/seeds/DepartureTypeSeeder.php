<?php

use Illuminate\Database\Seeder;

class DepartureTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [			
			['id'=> 1,'document_type_id' => 6, 'name' => 'Salida', 'description' => 'Solicitud directa'],
			['id'=> 2,'document_type_id' => 7, 'name' => 'Licencia', 'description' => 'Solicitud mediante presentación de nota']
		];

		foreach ($types as $type) {
			App\DepartureType::create($type);
		}
    }
}
