<?php

use Illuminate\Database\Seeder;

class PositionGroupAddressSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $position_groups = App\PositionGroup::get()->all();
    $addresses = App\CompanyAddress::get()->all();

    foreach ($position_groups as $position_group) {
      for ($i = 0; $i < rand(1, 2); $i++) {
        $position_group->addresses()->attach($addresses[array_rand($addresses)]->id);
      }
    }
  }
}
