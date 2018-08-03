<?php

use Illuminate\Database\Seeder;

class ManagementEntitySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $management_entities = [
      ['ovt_id' => 1, 'name' => 'PREVISION'],
      ['ovt_id' => 2, 'name' => 'FUTURO'],
    ];

    foreach($management_entities as $i => $management_entity) {
      App\ManagementEntity::create($management_entity);
    }
  }
}
