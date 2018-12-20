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
    App\Role::create([
      'name' => 'financiera',
      'display_name' => 'FINANCIERA'
    ]);
  }
}
