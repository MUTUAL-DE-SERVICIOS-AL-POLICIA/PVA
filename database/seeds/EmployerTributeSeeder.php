<?php

use Illuminate\Database\Seeder;

class EmployerTributeSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		factory(App\EmployerTribute::class)->create();
	}
}
