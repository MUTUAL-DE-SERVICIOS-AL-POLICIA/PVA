<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseProductionSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->command->info('Unguarding models');
    Model::unguard();

    $this->call(CitySeeder::class);
    $this->call(InsuranceCompanySeeder::class);
    $this->call(ManagementEntitySeeder::class);
    $this->call(RetirementReasonSeeder::class);
  }
}