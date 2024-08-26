<?php

use Illuminate\Database\Seeder;

class ConsultantPayrollSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$procedure = App\ConsultantProcedure::orderBy('created_at', 'DESC')->limit(1)->first();
		$month_shortened = str_pad($procedure->month->order, 2, '0', STR_PAD_LEFT);
		$year_shortened = substr((string)$procedure->year, -2);

		$contracts = App\ConsultantContract::get()->all();

		foreach ($contracts as $i => $contract) {
			App\ConsultantPayroll::create([
				'code' => implode('-', ['C', str_pad(++$i, 3, '0', STR_PAD_LEFT), $year_shortened]),
				'unworked_days' => rand(0, 3),
				'consultant_procedure_id' => $procedure->id,
				'consultant_contract_id' => $contract->id,
				'employee_id' => $contract->employee->id,
				'charge_id' => $contract->consultant_position->charge_id,
				'position_group_id' => $contract->consultant_position->position_group_id,
				'consultant_position_id' => $contract->consultant_position_id,
				'faults' => rand(0, 99999) / 100,
			]);
		}
	}
}
