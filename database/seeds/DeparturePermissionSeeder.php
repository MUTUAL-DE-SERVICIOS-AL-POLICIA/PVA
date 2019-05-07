<?php

use Illuminate\Database\Seeder;

class DeparturePermissionSeeder extends Seeder
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
        'name' => 'read-departure',
        'display_name' => 'Leer licencia',
        'description' => 'Permiso para leer datos de los licencias',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'update-departure',
        'display_name' => 'Editar licencia',
        'description' => 'Permiso para editar los datos de una licencia',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ]
    ];

    App\Permission::insert($permissions);
  }
}
