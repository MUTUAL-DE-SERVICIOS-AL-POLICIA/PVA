<?php

use Faker\Generator as Faker;

$factory->define(App\EmployerContribution::class, function (Faker $faker) {
	return [
		'insurance_company' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
		'professional_risk' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
		'solidary' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
		'housing' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
		'active' => true,
	];
});
