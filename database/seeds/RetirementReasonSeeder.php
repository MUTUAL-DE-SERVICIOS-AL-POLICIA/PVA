<?php

use Illuminate\Database\Seeder;

class RetirementReasonSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $reasons = [
      ['ovt_id' => 1, 'name' => 'Retiro voluntario del trabajador'],
      ['ovt_id' => 2, 'name' => 'Vencimiento de contrato'],
      ['ovt_id' => 3, 'name' => 'Conclusión de obra'],
      ['ovt_id' => 4, 'name' => 'Perjuicio material causado con intención en los instrumentos de trabajo'],
      ['ovt_id' => 5, 'name' => 'Revelación de secretos industriales'],
      ['ovt_id' => 6, 'name' => 'Omisiones o imprudencias que afecten a la seguridad o higiene industrial'],
      ['ovt_id' => 7, 'name' => 'Inasistencia injustificada de más de seis días continuos'],
      ['ovt_id' => 8, 'name' => 'Incumplimiento total o parcial del convenio'],
      ['ovt_id' => 9, 'name' => 'Robo o hurto por el trabajador'],
      ['ovt_id' => 10, 'name' => 'Retiro forzoso'],
    ];

    foreach ($reasons as $reason) {
      App\RetirementReason::create($reason);
    }
  }
}
