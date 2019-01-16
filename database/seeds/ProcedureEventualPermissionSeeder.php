<?php

use Illuminate\Database\Seeder;

class ProcedureEventualPermissionSeeder extends Seeder
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
        'name' => 'create-procedure-consultant',
        'display_name' => 'Crear planilla de consultores',
        'description' => 'Permiso para registrar una planilla de consultores',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'read-procedure-consultant',
        'display_name' => 'Leer planilla de consultores',
        'description' => 'Permiso para leer datos de planillas de consultores',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'update-procedure-consultant',
        'display_name' => 'Editar planilla de consultores',
        'description' => 'Permiso para editar los datos de una planilla de consultores',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'delete-procedure-consultant',
        'display_name' => 'Eliminar planilla de consultores',
        'description' => 'Permiso para eliminar una planilla de consultores',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ]
    ];

    App\Permission::insert($permissions);
  }
}
