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
      ['name' => 'Tiempo indefinido'],
      ['name' => 'A plazo fijo'],
      ['name' => 'Por temporada'],
      ['name' => 'Por realización de obra o servicio'],
      ['name' => 'Condicional o eventual'],
    ];

    foreach ($types as $type) {
      App\ContractType::create($type);
    }
  }
}
