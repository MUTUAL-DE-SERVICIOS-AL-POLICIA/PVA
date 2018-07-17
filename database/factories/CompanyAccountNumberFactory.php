<?php

use Faker\Generator as Faker;

$factory->define(App\CompanyAccountNumber::class, function (Faker $faker) {
  $faker->addProvider(new \Faker\Provider\Lorem($faker));
  $faker->addProvider(new \Faker\Provider\ms_MY\Payment($faker));

  return [
    'account_number' => $faker->unique()->randomNumber(8, true) * pow(10, 6),
    'financial_entity' => $faker->bank,
    'description' => $faker->sentence(),
  ];
});
