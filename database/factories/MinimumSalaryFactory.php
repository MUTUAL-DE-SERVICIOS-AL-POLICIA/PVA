<?php

use Faker\Generator as Faker;

$factory->define(App\MinimumSalary::class, function (Faker $faker) {
  return [
    'value' => $faker->randomFloat($nbMaxDecimals = 0, $min = 2000, $max = 3000)
  ];
});
