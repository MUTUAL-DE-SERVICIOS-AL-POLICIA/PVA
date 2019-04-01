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
      ['name' => 'PERSONAL', 'description' => 'Asuntos personales'],
      ['name' => 'COMISIÓN', 'description' => 'VIAJEs, reuniones, diligencias'],
      ['name' => 'SALUD', 'description' => 'Consulta médica, bajas'],
      ['name' => 'FAMILIAR', 'description' => 'Matrimonio, nacimientos, fallecimientos'],
      ['name' => 'EXTRACURRICULAR', 'description' => 'Otros']
    ];

    foreach ($types as $type) {
      App\DepartureGroup::create($type);
    }
  }
}
