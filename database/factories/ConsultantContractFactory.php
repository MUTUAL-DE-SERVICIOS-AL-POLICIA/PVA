<?php

use Faker\Generator as Faker;

$factory->define(App\ConsultantContract::class, function (Faker $faker) {
  $faker->addProvider(new \Faker\Provider\DateTime($faker));
  $faker->addProvider(new \Faker\Provider\Payment($faker));

  $insurance_companies = App\InsuranceCompany::get()->all();

  $start_date = $faker->dateTimeBetween($startDate = "2017-01-01", $endDate = "now")->format('Y-m-d');
  return [
    'start_date' => $faker->dateTimeBetween($startDate = $start_date, $endDate = "1 month")->format('Y-m-d'),
    'end_date' => $faker->dateTimeBetween($startDate = "now", $endDate = "1 year")->format('Y-m-d'),
    'rrhh_cite' => $faker->creditCardExpirationDateString(),
    'rrhh_cite_date' => $start_date,
    'contract_number' => $faker->creditCardExpirationDateString(),
  ];
});
