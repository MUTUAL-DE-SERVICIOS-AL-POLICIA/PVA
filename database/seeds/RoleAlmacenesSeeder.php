<?php

use Illuminate\Database\Seeder;

class RoleAlmacenesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Role::create([
            'name' => 'almacenes',
            'display_name' => 'ALMACENES'
        ]);
    }
}
