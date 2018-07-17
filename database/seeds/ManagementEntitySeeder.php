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
      ['name' => 'PREVISION'],
      ['name' => 'FUTURO'],
    ];

    foreach($management_entities as $management_entity) {
      App\ManagementEntity::create($management_entity);
    }
  }
}
