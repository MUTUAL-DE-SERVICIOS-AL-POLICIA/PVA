<?php

use Faker\Generator as Faker;

$factory->define(App\PositionGroup::class, function (Faker $faker) {
	$faker->addProvider(new \Faker\Provider\en_US\Company($faker));

	return [
		'name' => strtoupper($faker->unique()->catchPhrase),
		'shortened' => strtoupper($faker->unique()->firstname),
		'active' => true,
	];
});
