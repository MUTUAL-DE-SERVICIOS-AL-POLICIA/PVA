<?php

use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$actions = [
			['name' => 'create', 'description' => 'Acción de crear'],
			['name' => 'edit', 'description' => 'Acción de editar'],
			['name' => 'delete', 'description' => 'Acción de eliminar'],
		];

		foreach ($actions as $action) {
			App\Action::create($action);
		}
	}
}
