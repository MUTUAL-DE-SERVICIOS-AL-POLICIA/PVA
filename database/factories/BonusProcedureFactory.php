<?php

use Faker\Generator as Faker;

$factory->define(App\BonusProcedure::class, function (Faker $faker) {
  $years = App\BonusYear::get()->all();
  $year = $years[array_rand($years)]->year;

  return [
    'year' => $year,
    'pay_date' => Carbon::create($year, 12, $faker->numberBetween(5, 20))->toDateString()
  ];
});
