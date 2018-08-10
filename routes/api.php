<?php

Route::group([
	'middleware' => 'api',
	'prefix' => 'v1/auth',
], function ($router) {
	Route::post('login', 'AuthController@login');
	Route::get('logout', 'AuthController@logout');
	Route::get('refresh', 'AuthController@refresh');
	Route::get('me', 'AuthController@me');
});

if (config('app.debug')) {
	Route::group([
		'middleware' => ['api'],
		'prefix' => 'v1',
	], function ($router) {
		Route::resource('user', 'UserController')->except(['store', 'create', 'edit']);
		Route::resource('user_action', 'UserActionController')->except(['create', 'store', 'edit', 'update']);
		Route::resource('role', 'RoleController')->except(['store', 'create', 'edit', 'update', 'destroy']);
		Route::resource('company', 'CompanyController')->except(['create', 'edit', 'destroy']);
		Route::resource('employee', 'EmployeeController')->except(['create', 'edit']);
		Route::resource('city', 'CityController')->except(['store', 'create', 'edit', 'update', 'destroy']);
		Route::resource('management_entity', 'ManagementEntityController')->except(['store', 'create', 'edit', 'update', 'destroy']);
		Route::resource('insurance_company', 'InsuranceCompanyController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	});
} else {
	Route::group([
		'middleware' => ['api', 'jwt.auth'],
		'prefix' => 'v1',
	], function ($router) {
		Route::group([
			'middleware' => ['role:admin'],
		], function ($r) {
			Route::resource('user', 'UserController')->except(['store', 'create', 'edit']);
			Route::resource('user_action', 'UserActionController')->except(['create', 'store', 'edit', 'update']);
			Route::resource('role', 'RoleController')->except(['store', 'create', 'edit', 'update', 'destroy']);
		});
		Route::resource('company', 'CompanyController')->except(['create', 'edit', 'destroy']);
		Route::resource('employee', 'EmployeeController')->except(['create', 'edit']);
		Route::resource('city', 'CityController')->except(['store', 'create', 'edit', 'update', 'destroy']);
		Route::resource('management_entity', 'ManagementEntityController')->except(['store', 'create', 'edit', 'update', 'destroy']);
		Route::resource('insurance_company', 'InsuranceCompanyController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	});
}