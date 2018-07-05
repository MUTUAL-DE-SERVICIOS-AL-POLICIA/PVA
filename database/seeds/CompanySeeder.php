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
      for ($i = 0; $i < 5; $i++) {
        $company->account_numbers()->save(factory(App\CompanyAccountNumber::class)->make());
      }
    });
  }
}
