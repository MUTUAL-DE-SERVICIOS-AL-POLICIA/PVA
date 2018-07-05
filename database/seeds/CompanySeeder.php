<?php

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(App\Company::class)->create()->each(function ($company) {
      $total_account_numbers = 2;
      
      for ($i = 0; $i < $total_account_numbers; $i++) {
        $company->account_numbers()->save(factory(App\CompanyAccountNumber::class)->make());
      }
    });
  }
}
