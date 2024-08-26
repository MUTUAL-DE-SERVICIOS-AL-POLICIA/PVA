<?php

use Faker\Generator as Faker;

$factory->define(App\EmployerNumber::class, function (Faker $faker) {
  return [
    'number' => implode('-', [$faker->unique()->randomNumber(2, true), $faker->unique()->randomNumber(3, true), $faker->unique()->randomNumber(5, true)]),
  ];
});
