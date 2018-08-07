<?php

use Illuminate\Database\Seeder;

class EmployerContributionSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		factory(App\EmployerContribution::class)->create();
		$contribution = factory(App\EmployerContribution::class)->make();
		$contribution->active = false;
		$contribution->save();
	}
}
