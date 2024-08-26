<?php

use Illuminate\Database\Seeder;

class ProcedureConsultantPermissionSeeder extends Seeder
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
        'name' => 'create-procedure-eventual',
        'display_name' => 'Crear planilla de eventuales',
        'description' => 'Permiso para registrar una planilla de eventuales',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'read-procedure-eventual',
        'display_name' => 'Leer planilla de eventuales',
        'description' => 'Permiso para leer datos de planillas de eventuales',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'update-procedure-eventual',
        'display_name' => 'Editar planilla de eventuales',
        'description' => 'Permiso para editar los datos de una planilla de eventuales',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'delete-procedure-eventual',
        'display_name' => 'Eliminar planilla de eventuales',
        'description' => 'Permiso para eliminar una planilla de eventuales',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ]
    ];

    App\Permission::insert($permissions);
  }
}
