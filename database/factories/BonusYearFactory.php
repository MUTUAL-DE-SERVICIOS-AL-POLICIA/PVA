<?php

use Faker\Generator as Faker;

$factory->define(App\BonusYear::class, function (Faker $faker) {
  return [
    'year' => $faker->numberBetween(Carbon::now()->year, Carbon::now()->subYear($faker->numberBetween(1, 5))->year),
    'bonus' => $faker->numberBetween(1, 2)
  ];
});
