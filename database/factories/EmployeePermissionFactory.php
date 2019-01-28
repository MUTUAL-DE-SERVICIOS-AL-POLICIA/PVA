<?php

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
  $faker->addProvider(new \Faker\Provider\Lorem($faker));

  return [
    'name' => mb_strtoupper($faker->unique()->firstname),
    'display_name' => $faker->unique()->company,
    'description' => $faker->sentence(),
  ];
});
