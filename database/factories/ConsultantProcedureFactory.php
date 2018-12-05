<?php

use Faker\Generator as Faker;

$factory->define(App\ConsultantProcedure::class, function (Faker $faker) {
  return [
    'year' => Carbon::now()->year,
    'month_id' => Carbon::now()->month,
  ];
});
