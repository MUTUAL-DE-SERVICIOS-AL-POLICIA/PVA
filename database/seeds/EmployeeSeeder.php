<?php

use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $total_employees = 11;

    factory(App\Employee::class, $total_employees)->create();
  }
}
