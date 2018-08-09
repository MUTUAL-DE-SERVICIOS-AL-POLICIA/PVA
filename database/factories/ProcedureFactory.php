<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Procedure::class, function (Faker $faker) {
	$employee_discount = App\EmployeeDiscount::where('active', true)->first();
	$employee_contribution = App\EmployerContribution::where('active', true)->first();

	return [
		'year' => Carbon::now()->year,
		'month_id' => Carbon::now()->month,
		'employee_discount_id' => $employee_discount->id,
		'employer_contribution_id' => $employee_contribution->id,
		'active' => false,
	];
});
