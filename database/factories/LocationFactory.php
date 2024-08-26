<?php

use Faker\Generator as Faker;

$factory->define(App\Location::class, function (Faker $faker) {
  $faker->addProvider(new \Faker\Provider\en_US\Company($faker));
  $faker->addProvider(new \Faker\Provider\en_US\Payment($faker));

  $position_groups = App\PositionGroup::get()->all();

  return [
    'name' => $faker->unique()->company,
    'phone_number' => $faker->bankRoutingNumber(),
    'position_group_id' => $position_groups[array_rand($position_groups)]->id,
  ];
});
