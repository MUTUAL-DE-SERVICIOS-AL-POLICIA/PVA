<?php

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		factory(App\Company::class)->create()->each(function ($company) {
			$total_accounts = 2;
			$insurance_companies = App\InsuranceCompany::get()->all();
			$total_employer_numbers = count($insurance_companies);

			for ($i = 0; $i < $total_accounts; $i++) {
				$a = factory(App\CompanyAccount::class)->make();
				if ($i == 0) {
					$a->active = true;
				}
				$company->accounts()->save($a);
			}

			$employer_numbers = factory(App\EmployerNumber::class, $total_employer_numbers)->make()->all();
			$i = 0;
			foreach ($employer_numbers as $employer_number) {
				$employer_number->company_id = $company->id;
				$employer_number->insurance_company_id = $insurance_companies[$i]->id;
				$employer_number->save();
				$i++;
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
