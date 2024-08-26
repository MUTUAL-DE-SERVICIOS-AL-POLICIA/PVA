<?php

use Illuminate\Database\Seeder;

class AdminPermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $permisions = App\Permission::get()->toArray();
    $role = App\Role::where('name', 'admin')->first();

    $role->attachPermissions($permisions);
  }
}
