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
      $total_accounts = 2;
      $total_employer_numbers = 5;

      for ($i = 0; $i < $total_accounts; $i++) {
        $company->accounts()->save(factory(App\CompanyAccount::class)->make());
      }

      $employer_numbers = factory(App\EmployerNumber::class, $total_employer_numbers)->make()->all();
      $insurance_companies = App\InsuranceCompany::get()->all();
      foreach ($employer_numbers as $employer_number) {
        $employer_number->company_id = $company->id;
        $employer_number->insurance_company_id = $insurance_companies[array_rand($insurance_companies)]->id;
        $employer_number->save();
      }

      $cities = App\City::get()->all();
      $employer_numbers = App\EmployerNumber::get()->all();
      foreach ($cities as $city) {
        $city->employer_number_id = $employer_numbers[array_rand($employer_numbers)]->id;
        $city->save();
      }
    });
  }
}
