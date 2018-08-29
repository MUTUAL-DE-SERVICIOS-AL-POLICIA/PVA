<?php

use Faker\Generator as Faker;

$factory->define(App\EmployeeDiscount::class, function (Faker $faker) {
	return [
		'elderly' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
		'common_risk' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
		'comission' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
		'solidary' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
		'national' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
		'rc_iva' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
		'active' => true,
	];
});
