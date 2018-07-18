<?php

use Faker\Generator as Faker;

$factory->define(App\CompanyAddress::class, function (Faker $faker) {
  return [
    'address' => $faker->streetAddress(),
    'city_id' => rand(1, 9),
  ];
});
