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
			['name' => 'Particular', 'description' => ''],
			['name' => 'Comision', 'description' => ''],
			['name' => 'Licencia', 'description' => ''],
			['name' => 'Otros', 'description' => '']
		];

		foreach ($types as $type) {
			App\DepartureType::create($type);
		}
    }
}
