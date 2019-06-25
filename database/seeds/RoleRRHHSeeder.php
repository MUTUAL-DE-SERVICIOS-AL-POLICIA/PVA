<?php

use Illuminate\Database\Seeder;

class RoleRRHHSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $role = App\Role::firstOrNew([
      'name' => 'rrhh',
      'display_name' => 'RRHH'
    ]);

    $permisions = App\Permission::where('name', 'create-employee')->orWhere('name', 'read-employee')->orWhere('name', 'update-employee')->orWhere('name', 'delete-employee')->orWhere('name', 'create-eventual')->orWhere('name', 'read-eventual')->orWhere('name', 'update-eventual')->orWhere('name', 'delete-eventual')->orWhere('name', 'create-consultant')->orWhere('name', 'read-consultant')->orWhere('name', 'update-consultant')->orWhere('name', 'delete-consultant')->orWhere('name', 'create-procedure-consultant')->orWhere('name', 'read-procedure-consultant')->orWhere('name', 'update-procedure-consultant')->orWhere('name', 'create-procedure-eventual')->orWhere('name', 'read-procedure-eventual')->orWhere('name', 'update-procedure-eventual')->orWhere('name', 'read-departure')->orWhere('name', 'update-departure')->orWhere('name', 'read-user-action')->get()->toArray();

    $role->attachPermissions($permisions);
  }
}
