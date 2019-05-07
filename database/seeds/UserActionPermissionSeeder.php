<?php

use Illuminate\Database\Seeder;

class UserActionPermissionSeeder extends Seeder
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
        'name' => 'read-user-action',
        'display_name' => 'Leer acciones de usuarios',
        'description' => 'Permiso para leer datos de acciones de usuarios',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'delete-user-action',
        'display_name' => 'Eliminar accion de usuario',
        'description' => 'Permiso para eliminar una accion de usuario',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ]
    ];

    App\Permission::insert($permissions);
  }
}