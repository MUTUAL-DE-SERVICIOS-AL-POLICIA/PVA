<?php

use Illuminate\Database\Seeder;

class ContractTypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $types = [
      ['ovt_id' => 1, 'name' => 'Tiempo indefinido'],
      ['ovt_id' => 2, 'name' => 'A plazo fijo'],
      ['ovt_id' => 3, 'name' => 'Por temporada'],
      ['ovt_id' => 4, 'name' => 'Por realización de obra o servicio'],
      ['ovt_id' => 5, 'name' => 'Condicional o eventual'],
    ];

    foreach ($types as $type) {
      App\ContractType::create($type);
    }
  }
}
