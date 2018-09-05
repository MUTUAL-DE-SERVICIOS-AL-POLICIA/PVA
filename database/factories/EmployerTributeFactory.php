<?php

use Faker\Generator as Faker;

$factory->define(App\EmployerTribute::class, function (Faker $faker) {
	return [
		'minimum_salary' => $faker->randomFloat($nbMaxDecimals = 0, $min = 2000, $max = 3000),
		'ufv' => $faker->randomFloat($nbMaxDecimals = 3, $min = 2, $max = 3),
	];
});
