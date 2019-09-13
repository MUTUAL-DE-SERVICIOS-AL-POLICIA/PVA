<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class RoleWarehousesSeeder extends Seeder
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

      $permisions = App\Permission::where('name', 'read-provider')->orWhere('name', 'read-entrynote')->get()->toArray();

      $role->attachPermissions($permisions);
    }
}
