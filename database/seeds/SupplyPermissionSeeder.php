<?php

use Illuminate\Database\Seeder;

class SupplyPermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $permissions = [
      [
        'name' => 'read-supply-requests',
        'display_name' => 'Leer solicitudes almacén',
        'description' => 'Permiso para leer solicitudes de almacén',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'read-supplies',
        'display_name' => 'Leer material almacén',
        'description' => 'Permiso para leer material de almacén',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ]
    ];

    App\Permission::insert($permissions);
  }
}
