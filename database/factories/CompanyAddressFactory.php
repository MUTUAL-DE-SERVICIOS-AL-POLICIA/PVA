<?php

use Faker\Generator as Faker;

$factory->define(App\CompanyAddress::class, function (Faker $faker) {
  return [
    'address' => $faker->streetAddress(),
  ];
});
