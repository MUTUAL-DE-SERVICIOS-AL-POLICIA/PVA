<?php

$debug_middleware = (config('app.debug')) ? ['api'] : ['api', 'jwt.auth'];

Route::group([
	'middleware' => 'api',
	'prefix' => 'v1/auth',
], function ($router) {
	Route::post('login', 'AuthController@login');
	Route::get('logout', 'AuthController@logout');
	Route::get('refresh', 'AuthController@refresh');
	Route::get('me', 'AuthController@me');
});

Route::group([
	'middleware' => $debug_middleware,
	'prefix' => 'v1',
], function ($router) {
	Route::resource('company', 'CompanyController')->except(['create', 'edit', 'destroy']);
	Route::resource('employee', 'EmployeeController')->except(['create', 'edit']);
	Route::get('employee/list', 'EmployeeController@list');
});