<?php

use Illuminate\Database\Seeder;

class MinimumSalarySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $total_locations = 1;

    factory(App\MinimumSalary::class, $total_locations)->create();
  }
}
