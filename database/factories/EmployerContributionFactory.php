<?php

use Faker\Generator as Faker;

$factory->define(App\EmployerContribution::class, function (Faker $faker) {
	return [
		'insurance_company' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99),
		'professional_risk' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99),
		'solidary' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99),
		'housing' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99),
		'active' => true,
	];
});
