<?php

use Illuminate\Database\Seeder;

class RoleJuridicaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $role = App\Role::firstOrNew([
      'name' => 'juridica',
      'display_name' => 'JURIDICA'
    ]);

    $permisions = App\Permission::where('name', 'read-employee')->orWhere('name', 'read-eventual')->orWhere('name', 'update-eventual')->orWhere('name', 'read-consultant')->orWhere('name', 'update-consultant')->orWhere('name', 'read-user-action')->get()->toArray();

    $role->attachPermissions($permisions);
  }
}
