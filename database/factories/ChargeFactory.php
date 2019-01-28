<?php

use Faker\Generator as Faker;

$factory->define(App\Charge::class, function (Faker $faker) {
	$faker->addProvider(new \Faker\Provider\en_US\Company($faker));

	return [
		'name' => mb_strtoupper($faker->unique()->catchPhrase),
		'base_wage' => $faker->randomNumber(4, true),
		'active' => true,
	];
});
