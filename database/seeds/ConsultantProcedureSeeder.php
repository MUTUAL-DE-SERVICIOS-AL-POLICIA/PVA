<?php

use Illuminate\Database\Seeder;

class ConsultantProcedureSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(App\ConsultantProcedure::class)->create();
  }
}
