<?php

use Faker\Generator as Faker;
use Faker\Provider\DateTime;

$factory->define(App\Company::class, function (Faker $faker) {
  $faker->addProvider(new \Faker\Provider\en_US\Company($faker));

  return [
    'name' => $faker->unique()->company,
    'shortened' => mb_strtoupper($faker->unique()->firstname),
    'tax_number' => $faker->unique()->randomNumber(8, true) * pow(10, 6),
    'directors_designation_number' => $faker->randomNumber(5, true),
    'directors_designation_date' => $faker->date($format = 'Y-m-d', $max = 'now')
  ];
});
