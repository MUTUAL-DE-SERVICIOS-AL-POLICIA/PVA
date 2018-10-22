<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
  return [
    'username' => $faker->unique()->firstname,
    'name' => strtoupper(implode(' ', [$faker->firstname, $faker->firstname])),
    'position' => strtoupper($faker->unique()->catchPhrase),
    'password' => Hash::make('secret'),
  ];
});