<?php

use Illuminate\Database\Seeder;

class ConsultantContractSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$employees = App\Employee::get()->all();
		$positions = App\ConsultantPosition::get()->all();
		$job_schedules = App\JobSchedule::get()->all();

		$contracts = factory(App\ConsultantContract::class, count($employees))->make();

		foreach ($contracts as $i => $contract) {
			$contract->employee_id = $employees[$i]->id;
			$contract->consultant_position_id = $positions[array_rand($positions)]->id;
			$contract->save();

			for ($i = 0; $i < rand(1, 2); $i++) {
				$contract->job_schedules()->attach($job_schedules[$i]);
			}
		}
	}
}
