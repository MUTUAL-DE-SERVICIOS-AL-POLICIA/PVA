<?php

use Faker\Generator as Faker;

$factory->define(App\PositionGroup::class, function (Faker $faker) {
  $faker->addProvider(new \Faker\Provider\en_US\Company($faker));

  $company_addresses = App\CompanyAddress::get()->all();

  return [
    'name' => $faker->unique()->catchPhrase,
    'shortened' => strtoupper($faker->unique()->firstname),
    'company_address_id' => $company_addresses[array_rand($company_addresses)]->id,
  ];
});
