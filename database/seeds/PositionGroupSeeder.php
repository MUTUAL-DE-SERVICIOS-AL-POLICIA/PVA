<?php

use Illuminate\Database\Seeder;

class PositionGroupSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $total_position_groups = 11;

    factory(App\PositionGroup::class, $total_position_groups)->create();

    $position_groups = App\PositionGroup::get()->all();
    $j = 2;
    for($i = 0; $i <= 1; $i++) {
      for($x = 0; $x < ($total_position_groups/2)-1; $x++) {
        $position_groups[$i]->dependents()->attach($j);
        $j++;
      }
    }
  }
}
