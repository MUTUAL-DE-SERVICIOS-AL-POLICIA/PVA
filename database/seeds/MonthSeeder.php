<?php

use Illuminate\Database\Seeder;

class MonthSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$months = [
			['order' => 1, 'name' => 'ENERO', 'shortened' => 'ENE'],
			['order' => 2, 'name' => 'FEBRERO', 'shortened' => 'FEB'],
			['order' => 3, 'name' => 'MARZO', 'shortened' => 'MAR'],
			['order' => 4, 'name' => 'ABRIL', 'shortened' => 'ABR'],
			['order' => 5, 'name' => 'MAYO', 'shortened' => 'MAY'],
			['order' => 6, 'name' => 'JUNIO', 'shortened' => 'JUN'],
			['order' => 7, 'name' => 'JULIO', 'shortened' => 'JUL'],
			['order' => 8, 'name' => 'AGOSTO', 'shortened' => 'AGO'],
			['order' => 9, 'name' => 'SEPTIEMBRE', 'shortened' => 'SEP'],
			['order' => 10, 'name' => 'OCTUBRE', 'shortened' => 'OCT'],
			['order' => 11, 'name' => 'NOVIEMBRE', 'shortened' => 'NOV'],
			['order' => 12, 'name' => 'DICIEMBRE', 'shortened' => 'DIC'],
		];

		foreach ($months as $month) {
			App\Month::create($month);
		}
	}
}
