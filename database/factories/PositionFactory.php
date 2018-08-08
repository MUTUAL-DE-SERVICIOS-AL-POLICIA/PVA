<?php

use Faker\Generator as Faker;

$factory->define(App\Position::class, function (Faker $faker) {
	$faker->addProvider(new \Faker\Provider\en_US\Company($faker));

	$charges = App\Charge::get()->all();
	$position_groups = App\PositionGroup::get()->all();

	return [
		'name' => $faker->unique()->catchPhrase,
		'item' => $faker->randomNumber(2, true),
		'charge_id' => $charges[array_rand($charges)]->id,
		'position_group_id' => $position_groups[array_rand($position_groups)]->id,
		'active' => true,
	];
});
