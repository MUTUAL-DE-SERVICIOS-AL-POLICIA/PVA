<?php

use Faker\Generator as Faker;

$factory->define(App\DocumentType::class, function (Faker $faker) {
	$faker->addProvider(new \Faker\Provider\en_US\Company($faker));
	$faker->addProvider(new \Faker\Provider\Payment($faker));

	return [
		'name' => $faker->unique()->company,
		'shortened' => $faker->creditCardExpirationDateString(),
	];
});
