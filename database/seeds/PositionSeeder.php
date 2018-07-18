<?php

use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $total_positions = 50;

    factory(App\Position::class, $total_positions)->create();
  }
}
