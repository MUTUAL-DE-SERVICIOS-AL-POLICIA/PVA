<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
  return [
    'username' => $faker->unique()->firstname,
    'password' => Hash::make('secret'),
  ];
});