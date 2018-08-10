<?php

use Faker\Generator as Faker;

$factory->define(App\UserAction::class, function (Faker $faker) {
	$users = App\User::get()->all();
	$methods = ['POST', 'PUT', 'PATCH', 'DELETE'];
	$routes = Route::getRoutes();
	$paths = array();
	$employees = App\Employee::get()->all();

	foreach ($routes as $route) {
		$paths[] = $route->uri();
	}

	return [
		'user_id' => $users[array_rand($users)]->id,
		'method' => $methods[array_rand($methods)],
		'path' => $paths[array_rand($paths)],
		'data' => json_encode($employees[array_rand($employees)]),
	];
});