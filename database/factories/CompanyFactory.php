<?php

use Faker\Generator as Faker;

$factory->define(App\Company::class, function (Faker $faker) {
  return [
    'name' => $faker->unique()->name,
    'shortened' => str_random(8),
  ];
});
