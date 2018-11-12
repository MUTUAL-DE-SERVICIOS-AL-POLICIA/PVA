<?php

use Illuminate\Database\Seeder;

class ContractTypeDepartureReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [			
			['contract_type_id'=> 1, 'departure_reason' => 1],
			['contract_type_id'=> 1, 'departure_reason' => 2],
			['contract_type_id'=> 1, 'departure_reason' => 3],
			['contract_type_id'=> 1, 'departure_reason' => 4],
			['contract_type_id'=> 1, 'departure_reason' => 5],
			['contract_type_id'=> 1, 'departure_reason' => 6],
			['contract_type_id'=> 1, 'departure_reason' => 7],
			['contract_type_id'=> 1, 'departure_reason' => 8],
			['contract_type_id'=> 1, 'departure_reason' => 9],
			['contract_type_id'=> 1, 'departure_reason' => 10],
			['contract_type_id'=> 1, 'departure_reason' => 11],
			['contract_type_id'=> 1, 'departure_reason' => 12],
			['contract_type_id'=> 1, 'departure_reason' => 13],
			['contract_type_id'=> 1, 'departure_reason' => 14],
			['contract_type_id'=> 1, 'departure_reason' => 15],
			['contract_type_id'=> 1, 'departure_reason' => 16],
			['contract_type_id'=> 1, 'departure_reason' => 17],
			['contract_type_id'=> 1, 'departure_reason' => 18],
			['contract_type_id'=> 1, 'departure_reason' => 19],
			['contract_type_id'=> 1, 'departure_reason' => 20],
			['contract_type_id'=> 1, 'departure_reason' => 21],
			['contract_type_id'=> 1, 'departure_reason' => 22],

			['contract_type_id'=> 5, 'departure_reason' => 1],
			['contract_type_id'=> 5, 'departure_reason' => 2],
			['contract_type_id'=> 5, 'departure_reason' => 3],
			['contract_type_id'=> 5, 'departure_reason' => 4],
			['contract_type_id'=> 5, 'departure_reason' => 5],
			['contract_type_id'=> 5, 'departure_reason' => 6],
			['contract_type_id'=> 5, 'departure_reason' => 7],
			['contract_type_id'=> 5, 'departure_reason' => 8],
			['contract_type_id'=> 5, 'departure_reason' => 9],
			['contract_type_id'=> 5, 'departure_reason' => 10],
			['contract_type_id'=> 5, 'departure_reason' => 11],
			['contract_type_id'=> 5, 'departure_reason' => 12],
			['contract_type_id'=> 5, 'departure_reason' => 13],
			['contract_type_id'=> 5, 'departure_reason' => 14],
			['contract_type_id'=> 5, 'departure_reason' => 15],
			['contract_type_id'=> 5, 'departure_reason' => 16],
			['contract_type_id'=> 5, 'departure_reason' => 17],
			['contract_type_id'=> 5, 'departure_reason' => 18],
			['contract_type_id'=> 5, 'departure_reason' => 19],
			['contract_type_id'=> 5, 'departure_reason' => 20],
			['contract_type_id'=> 5, 'departure_reason' => 21],
			['contract_type_id'=> 5, 'departure_reason' => 22],

			['contract_type_id'=> 4, 'departure_reason' => 2],
			['contract_type_id'=> 4, 'departure_reason' => 3],
			['contract_type_id'=> 4, 'departure_reason' => 4],
			['contract_type_id'=> 4, 'departure_reason' => 5],
			['contract_type_id'=> 4, 'departure_reason' => 6],
		];

		foreach ($types as $type) {
			$contract_type = App\ContractType::find($type['contract_type_id']);
			$contract_type->departure_reasons()->attach($type['departure_reason']);
		}
    }
}
