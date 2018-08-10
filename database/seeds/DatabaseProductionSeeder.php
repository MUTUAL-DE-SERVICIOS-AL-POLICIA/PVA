<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseProductionSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run() {
		$this->command->info('Unguarding models');
		Model::unguard();

		$this->call(CitySeeder::class);
		$this->call(InsuranceCompanySeeder::class);
		$this->call(ManagementEntitySeeder::class);
		$this->call(RetirementReasonSeeder::class);
		$this->call(ContractTypeSeeder::class);
	}
}