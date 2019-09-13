<?php

use App\Permission;
use Illuminate\Database\Seeder;

class WarehousesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //
      $permissions = [
        [
          'name' => 'read-provider',
          'display_name' => 'Leer datos del proveedor',
          'description' => 'Permiso para leer datos del proveedor',
          'created_at' => new \dateTime,
          'updated_at' => new \dateTime,
        ], [
          'name' => 'read-entrynote',
          'display_name' => 'Leer datos de la nota de ingreso',
          'description' => 'Permiso para leer datos de la nota de ingreso',
          'created_at' => new \dateTime,
          'updated_at' => new \dateTime,
        ]
      ];

      App\Permission::insert($permissions);



    }
}