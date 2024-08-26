<?php

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $cities = [
      ['name' => 'BENI', 'shortened' => 'BN'],
      ['name' => 'CHUQUISACA', 'shortened' => 'CH'],
      ['name' => 'COCHABAMBA', 'shortened' => 'CB'],
      ['name' => 'LA PAZ', 'shortened' => 'LP'],
      ['name' => 'ORURO', 'shortened' => 'OR'],
      ['name' => 'PANDO', 'shortened' => 'PD'],
      ['name' => 'POTOSI', 'shortened' => 'PT'],
      ['name' => 'SANTA CRUZ', 'shortened' => 'SC'],
      ['name' => 'TARIJA', 'shortened' => 'TJ']
    ];

    foreach ($cities as $city) {
      App\City::create($city);
    }
  }
}
