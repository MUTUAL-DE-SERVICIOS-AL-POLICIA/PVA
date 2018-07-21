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
    $total_positions = 11;

    factory(App\Position::class, $total_positions)->create();

    $positions = App\Position::get()->all();
    $j = 2;
    for($i = 0; $i <= 1; $i++) {
      for($x = 0; $x < ($total_positions/2)-1; $x++) {
        $positions[$i]->dependents()->attach($j);
        $j++;
      }
    }
  }
}
