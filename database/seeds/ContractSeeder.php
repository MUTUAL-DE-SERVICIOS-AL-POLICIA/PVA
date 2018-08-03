<?php

use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$employees = App\Employee::get()->all();
		$positions = App\Position::get()->all();
		$contract_types = App\ContractType::get()->all();
		$contract_modes = App\ContractMode::get()->all();
		$job_schedules = App\JobSchedule::get()->all();

		$contracts = factory(App\Contract::class, count($employees))->make();

		foreach ($contracts as $i => $contract) {
			$contract->employee_id = $employees[$i]->id;
			$contract->position_id = $positions[array_rand($positions)]->id;
			$contract->contract_type_id = $contract_types[array_rand($contract_types)]->id;
			$contract->contract_mode_id = $contract_modes[array_rand($contract_modes)]->id;
			$contract->job_schedule_id = $job_schedules[array_rand($job_schedules)]->id;
			$contract->save();
		}
	}
}
