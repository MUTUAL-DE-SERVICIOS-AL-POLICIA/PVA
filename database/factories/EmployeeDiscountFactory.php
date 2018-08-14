<?php

use Faker\Generator as Faker;

$factory->define(App\EmployeeDiscount::class, function (Faker $faker) {
	return [
		'elderly' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99),
		'common_risk' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99),
		'comission' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99),
		'solidary' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99),
		'national' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99),
		'rc_iva' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99),
		'active' => true,
	];
});
