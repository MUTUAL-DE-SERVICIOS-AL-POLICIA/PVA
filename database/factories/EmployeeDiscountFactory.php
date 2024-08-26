<?php

use Faker\Generator as Faker;

$factory->define(App\EmployeeDiscount::class, function (Faker $faker) {
  return [
    'elderly' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
    'common_risk' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
    'comission' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
    'solidary' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
    'national_limits' => [rand(12000, 14000), rand(14000, 16000), rand(16000, 18000)],
    'national_percentages' => [rand(1, 5), rand(5, 10), rand(10, 15)],
    'rc_iva' => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 0.1),
    'active' => true,
  ];
});
