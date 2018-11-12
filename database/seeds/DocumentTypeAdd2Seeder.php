<?php

use Illuminate\Database\Seeder;

class DocumentTypeAdd2Seeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$types = [
			['id' => 6, 'name' => 'Salida', 'shortened' => 'S'],
			['id' => 7, 'name' => 'Licencia', 'shortened' => 'L']
		];

		foreach ($types as $type) {
			App\DocumentType::create($type);
		}
	}
}
