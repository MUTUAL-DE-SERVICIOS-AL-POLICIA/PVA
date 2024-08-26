<?php

use Illuminate\Database\Seeder;

class EventualPermissionSeeder extends Seeder
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
        'name' => 'create-eventual',
        'display_name' => 'Crear eventual',
        'description' => 'Permiso para registrar un nuevo eventual',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'read-eventual',
        'display_name' => 'Leer eventual',
        'description' => 'Permiso para leer datos de los eventuales',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'update-eventual',
        'display_name' => 'Editar eventual',
        'description' => 'Permiso para editar los datos de un eventual',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'delete-eventual',
        'display_name' => 'Eliminar eventual',
        'description' => 'Permiso para eliminar un eventual',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'update-eventual-inactive',
        'display_name' => 'Editar eventual inactivo',
        'description' => 'Permiso para editar los datos de un eventual inactivo',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ]
    ];

    App\Permission::insert($permissions);
  }
}
