<?php

use Illuminate\Database\Seeder;
use App\Permission;

class EmployeePermissionSeeder extends Seeder
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
        'name' => 'read-employee',
        'display_name' => 'Leer empleado',
        'description' => 'Permiso para leer datos de los empleados',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'create-employee',
        'display_name' => 'Crear empleado',
        'description' => 'Permiso para registrar un nuevo empleado',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'update-employee',
        'display_name' => 'Editar empleado',
        'description' => 'Permiso para editar los datos de un empleado',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'delete-employee',
        'display_name' => 'Eliminar empleado',
        'description' => 'Permiso para eliminar un empleado',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'update-employee-inactive',
        'display_name' => 'Editar empleado inactivo',
        'description' => 'Permiso para editar los datos de un empleado inactivo',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ]
    ];

    App\Permission::insert($permissions);
  }
}