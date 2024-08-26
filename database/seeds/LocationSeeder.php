<?php

use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $total_locations = 20;

    factory(App\Location::class, $total_locations)->create();
  }
}
