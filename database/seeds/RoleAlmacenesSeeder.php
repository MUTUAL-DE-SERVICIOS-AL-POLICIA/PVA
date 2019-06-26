<?php

use Illuminate\Database\Seeder;

class RoleAlmacenesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $role = App\Role::firstOrNew([
      'name' => 'almacenes',
      'display_name' => 'ALMACENES'
    ]);

    $permisions = App\Permission::where('name', 'read-supply-requests')->orWhere('name', 'read-employee')->orWhere('name', 'read-supplies')->get()->toArray();

    $role->attachPermissions($permisions);
  }
}
