<?php

use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$procedure = App\Procedure::orderBy('created_at', 'DESC')->limit(1)->first();
		$month_shortened = str_pad($procedure->month->order, 2, '0', STR_PAD_LEFT);
		$year_shortened = substr((string) $procedure->year, -2);

		$contracts = App\Contract::get()->all();

		foreach ($contracts as $i => $contract) {
			App\Payroll::create([
				'code' => implode([str_pad(++$i, 3, '0', STR_PAD_LEFT), $month_shortened, $year_shortened], '-'),
				'unworked_days' => rand(0, 3),
				'procedure_id' => $procedure->id,
				'contract_id' => $contract->id,
				'charge_id' => $contract->position->charge_id,
				'position_group_id' => $contract->position->position_group_id,
				'position_id' => $contract->position_id,
				'next_month_balance' => rand(0, 50),
				'previous_month_balance' => rand(0, 100),
			]);
		}
	}
}
