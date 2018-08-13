<?php

use Illuminate\Database\Seeder;

class DocumentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'Decreto Supremo', 'shortened' => 'D.S.'],
            ['name' => 'Resolución Administrativa', 'shortened' => 'R.S.'],
            ['name' => 'Informe Técnico', 'shortened' => 'I.T.'],
            ['name' => 'Informe Legal', 'shortened' => 'I.L.'],
        ];

        foreach ($types as $type) {
            App\DocumentType::create($type);
        }
    }
}
