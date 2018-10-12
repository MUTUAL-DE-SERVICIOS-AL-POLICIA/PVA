<?php

use Illuminate\Database\Seeder;

class DocumentTypeAddSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$types = [
			['name' => 'Certificado de haberes y aportes laborales', 'shortened' => 'C.T.']
		];

		foreach ($types as $type) {
			App\DocumentType::create($type);
		}
	}
}
