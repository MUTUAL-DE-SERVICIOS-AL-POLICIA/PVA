<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
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
    
    $tables = [
      'company_account_numbers',
      'companies',
      'users',
      'cities',
      'company_addresses',
      'insurance_companies',
      'management_entities',
    ];
    
    $this->command->info('Truncating existing tables');
    foreach ($tables as $table) {
      DB::statement('TRUNCATE TABLE ' . $table . ' CASCADE;');
    }

    $this->call(CompanySeeder::class);
    $this->call(UserSeeder::class);
    $this->call(CitySeeder::class);
    $this->call(CompanyAddressSeeder::class);
    $this->call(InsuranceCompanySeeder::class);
    $this->call(ManagementEntitySeeder::class);
  }
}
