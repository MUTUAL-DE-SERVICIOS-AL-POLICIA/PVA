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
      ['name' => 'Retiro voluntario del trabajador'],
      ['name' => 'Vencimiento de contrato'],
      ['name' => 'Conclusión de obra'],
      ['name' => 'Perjuicio material causado con intención en los instrumentos de trabajo'],
      ['name' => 'Revelación de secretos industriales'],
      ['name' => 'Omisiones o imprudencias que afecten a la seguridad o higiene industrial'],
      ['name' => 'Inasistencia injustificada de más de seis días continuos'],
      ['name' => 'Incumplimiento total o parcial del convenio'],
      ['name' => 'Robo o hurto por el trabajador'],
      ['name' => 'Retiro forzoso'],
    ];

    foreach ($reasons as $reason) {
      App\RetirementReason::create($reason);
    }
  }
}
