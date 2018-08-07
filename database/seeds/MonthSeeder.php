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
			['order' => 1, 'name' => 'Enero', 'shortened' => 'ENE'],
			['order' => 2, 'name' => 'Febrero', 'shortened' => 'FEB'],
			['order' => 3, 'name' => 'Marzo', 'shortened' => 'MAR'],
			['order' => 4, 'name' => 'Abril', 'shortened' => 'ABR'],
			['order' => 5, 'name' => 'Mayo', 'shortened' => 'MAY'],
			['order' => 6, 'name' => 'Junio', 'shortened' => 'JUN'],
			['order' => 7, 'name' => 'Julio', 'shortened' => 'JUL'],
			['order' => 8, 'name' => 'Agosto', 'shortened' => 'AGO'],
			['order' => 9, 'name' => 'Septiembre', 'shortened' => 'SEP'],
			['order' => 10, 'name' => 'Octubre', 'shortened' => 'OCT'],
			['order' => 11, 'name' => 'Noviembre', 'shortened' => 'NOV'],
			['order' => 12, 'name' => 'Diciembre', 'shortened' => 'DIC'],
		];

		foreach ($months as $month) {
			App\Month::create($month);
		}
	}
}
