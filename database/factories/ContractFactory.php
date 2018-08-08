<?php

use Faker\Generator as Faker;

$factory->define(App\Contract::class, function (Faker $faker) {
	$faker->addProvider(new \Faker\Provider\DateTime($faker));
	$faker->addProvider(new \Faker\Provider\Payment($faker));

	$start_date = $faker->dateTimeBetween($startDate = "2017-01-01", $endDate = "now")->format('Y-m-d');
	return [
		'start_date' => $faker->dateTimeBetween($startDate = $start_date, $endDate = "1 month")->format('Y-m-d'),
		'end_date' => $faker->dateTimeBetween($startDate = "now", $endDate = "1 year")->format('Y-m-d'),
		'rrhh_cite' => $faker->creditCardExpirationDateString(),
		'rrhh_cite_date' => $start_date,
		'performance_cite' => $faker->creditCardExpirationDateString(),
		'insurance_number' => $faker->swiftBicNumber(),
		'contract_number' => $faker->creditCardExpirationDateString(),
		'hiring_reference_number' => $faker->creditCardExpirationDateString(),
	];
});