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
	Route::group([
		'middleware' => ['role:admin'],
	], function ($r) {
		Route::resource('user', 'UserController')->except(['store', 'create', 'edit']);
	});
	Route::resource('company', 'CompanyController')->except(['create', 'edit', 'destroy']);
	Route::resource('employee', 'EmployeeController')->except(['create', 'edit']);
	Route::resource('charge', 'ChargeController')->except(['create', 'edit']);	
	Route::resource('city', 'CityController')->except(['create', 'edit','store','show','update','destroy']);
	Route::resource('management_entity', 'ManagementEntityController')->except(['create', 'edit','store','show','update','destroy']);
	Route::resource('insurance_company', 'InsuranceCompanyController')->except(['create', 'edit','store','show','update','destroy']);
	Route::resource('position', 'PositionController')->except(['create', 'edit']);
});