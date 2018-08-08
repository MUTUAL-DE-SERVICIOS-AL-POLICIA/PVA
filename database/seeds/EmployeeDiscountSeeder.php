<?php

use Illuminate\Database\Seeder;

class EmployeeDiscountSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		factory(App\EmployeeDiscount::class)->create();
		$discount = factory(App\EmployeeDiscount::class)->make();
		$discount->active = false;
		$discount->save();
	}
}
