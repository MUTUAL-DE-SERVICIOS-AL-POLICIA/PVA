<?php

use Faker\Generator as Faker;

$factory->define(App\UserAction::class, function (Faker $faker) {
	$faker->addProvider(new \Faker\Provider\Lorem($faker));

	$users = App\User::get()->all();

	return [
		'user_id' => $users[array_rand($users)]->id,
		'action' => $faker->sentence()
	];
});