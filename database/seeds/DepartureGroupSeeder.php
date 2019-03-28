<?php

use Illuminate\Database\Seeder;

class DepartureGroupSeeder extends Seeder
{
  /**
     * Run the database seeds.
     *
     * @return void
     */
  public function run()
  {
    $types = [
      ['name' => 'Personal', 'description' => 'Asuntos personales'],
      ['name' => 'Comisión', 'description' => 'Viajes, reuniones, diligencias'],
      ['name' => 'Salud', 'description' => 'Atención médica, bajas'],
      ['name' => 'Familiar', 'description' => 'Matrimonio, nacimientos, fallecimientos'],
      ['name' => 'Extracurricular', 'description' => 'Otros']
    ];

    foreach ($types as $type) {
      App\DepartureGroup::create($type);
    }
  }
}
