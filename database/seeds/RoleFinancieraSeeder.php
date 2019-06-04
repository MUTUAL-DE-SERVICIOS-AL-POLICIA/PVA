<?php

use Illuminate\Database\Seeder;

class RoleFinancieraSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $role = App\Role::firstOrNew([
      'name' => 'financiera',
      'display_name' => 'FINANCIERA'
    ]);

    $permisions = App\Permission::where('name', 'read-employee')->orWhere('name', 'read-eventual')->orWhere('name', 'read-consultant')->orWhere('name', 'read-procedure-consultant')->orWhere('name', 'read-procedure-eventual')->orWhere('name', 'read-user-action')->get()->toArray();

    $role->attachPermissions($permisions);
  }
}
