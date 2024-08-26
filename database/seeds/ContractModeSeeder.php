<?php

use Illuminate\Database\Seeder;

class ContractModeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $modes = [
      ['ovt_id' => 1, 'name' => 'Escrito'],
      ['ovt_id' => 2, 'name' => 'Verbal'],
    ];

    foreach ($modes as $mode) {
      App\ContractMode::create($mode);
    }
  }
}
