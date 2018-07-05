<?php

use Faker\Generator as Faker;

$factory->define(App\CompanyAccountNumber::class, function (Faker $faker) {
  return [
    'account_number' => $faker->randomNumber(8, true) * pow(10, 6)
  ];
});
