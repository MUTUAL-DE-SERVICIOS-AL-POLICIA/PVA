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
    ];
    
    $this->command->info('Truncating existing tables');
    foreach ($tables as $table) {  
        DB::statement('TRUNCATE TABLE ' . $table . ' CASCADE;');
    }

    $this->call(CompanySeeder::class);
  }
}
