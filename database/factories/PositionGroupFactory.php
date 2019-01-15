<?php

use Faker\Generator as Faker;

$factory->define(App\PositionGroup::class, function (Faker $faker) {
	$faker->addProvider(new \Faker\Provider\en_US\Company($faker));
	$addresses = App\CompanyAddress::get()->all();

	return [
		'name' => mb_strtoupper($faker->unique()->catchPhrase),
		'shortened' => mb_strtoupper($faker->unique()->firstname),
		'active' => true,
		'company_address_id' => $addresses[array_rand($addresses)],
	];
});
