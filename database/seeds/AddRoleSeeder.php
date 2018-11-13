<?php

use Illuminate\Database\Seeder;

class AddRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [			
			['id'=> 4,'name' => 'empleado', 'display_name' => 'EMPLEADO', 'description' => 'Empleado normal']
		];

		foreach ($types as $type) {
			App\Role::create($type);
		}
    }
}
