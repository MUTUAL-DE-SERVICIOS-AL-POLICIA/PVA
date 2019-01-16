<?php

use Illuminate\Database\Seeder;

class ConsultantPermissionSeeder extends Seeder
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
        'name' => 'create-consultant',
        'display_name' => 'Crear consultor',
        'description' => 'Permiso para registrar un nuevo consultor',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'read-consultant',
        'display_name' => 'Leer consultor',
        'description' => 'Permiso para leer datos de los consultores',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'update-consultant',
        'display_name' => 'Editar consultor',
        'description' => 'Permiso para editar los datos de un consultor',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'delete-consultant',
        'display_name' => 'Eliminar consultor',
        'description' => 'Permiso para eliminar un consultor',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'update-consultant-inactive',
        'display_name' => 'Editar consultor inactivo',
        'description' => 'Permiso para editar los datos de un consultor inactivo',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ]
    ];

    App\Permission::insert($permissions);
  }
}
