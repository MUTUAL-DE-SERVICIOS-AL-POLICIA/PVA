<?php

use Faker\Generator as Faker;

$factory->define(App\ConsultantPosition::class, function (Faker $faker) {
  $charges = App\Charge::get()->all();
  $position_groups = App\PositionGroup::get()->all();

  return [
    'name' => mb_strtoupper($faker->unique()->catchPhrase),
    'charge_id' => $charges[array_rand($charges)]->id,
    'position_group_id' => $position_groups[array_rand($position_groups)]->id
  ];
});
