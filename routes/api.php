<?php

Route::macro('common_routes', function () {
	Route::resource('company', 'Api\V1\CompanyController')->except(['create', 'edit', 'destroy']);
	Route::resource('employee', 'Api\V1\EmployeeController')->except(['create', 'edit']);
	Route::resource('city', 'Api\V1\CityController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	Route::resource('management_entity', 'Api\V1\ManagementEntityController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	Route::resource('insurance_company', 'Api\V1\InsuranceCompanyController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	Route::resource('charge', 'Api\V1\ChargeController')->except(['create', 'edit']);
	Route::resource('position', 'Api\V1\PositionController')->except(['create', 'edit']);
	Route::resource('contract', 'Api\V1\ContractController')->except(['create', 'edit']);
	Route::resource('jobs_chedule', 'Api\V1\JobScheduleController')->except(['create', 'edit']);
	Route::resource('document', 'Api\V1\DocumentController')->except(['create', 'edit']);
  Route::resource('procedure', 'Api\V1\ProcedureController')->except(['create', 'edit']);
	Route::group([
		'prefix' => 'employee/{employee_id}/contract',
	], function () {
		Route::get('', 'Api\V1\EmployeeContractController@get_contracts');
		Route::group([
			'prefix' => '/{contract_id}',
		], function () {
			Route::get('', 'Api\V1\EmployeeContractController@get_contract');
		});
	});
	Route::resource('employer_number', 'Api\V1\EmployerNumberController')->except(['create', 'edit']);
	Route::group([
		'prefix' => 'employer_number/{employer_number_id}/insurance_company/{insurance_company_id}',
	], function () {
		Route::get('', 'Api\V1\EmployerNumberInsuranceCompanyController@get_insurance_company');
		Route::patch('', 'Api\V1\EmployerNumberInsuranceCompanyController@set_insurance_company');
	});
});

Route::macro('general_routes', function () {
	Route::resource('company_account', 'Api\V1\CompanyAccountController')->except(['create', 'edit']);
	Route::resource('insurance_company', 'Api\V1\InsuranceCompanyController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	Route::resource('company_address', 'Api\V1\CompanyAddressController')->except(['create', 'edit']);
	Route::group([
		'prefix' => 'company_address/{company_address_id}/city/{city_id}',
	], function () {
		Route::get('', 'Api\V1\CompanyAddressCityController@get_city');
		Route::patch('', 'Api\V1\CompanyAddressCityController@set_city');
	});
	Route::resource('position_group', 'Api\V1\PositionGroupController')->except(['create', 'edit']);
	Route::group([
		'prefix' => 'position_group/{superior_id}',
	], function () {
		Route::get('/dependency', 'Api\V1\DependencyPositionGroupController@get_dependency');
		Route::get('/dependents', 'Api\V1\DependencyPositionGroupController@get_dependents');
		Route::group([
			'prefix' => '/dependents/{dependent_id}',
		], function () {
			Route::get('', 'Api\V1\DependencyPositionGroupController@get_dependent');
			Route::patch('', 'Api\V1\DependencyPositionGroupController@set_dependent');
			Route::delete('', 'Api\V1\DependencyPositionGroupController@unset_dependent');
		});
	});
	Route::group([
		'prefix' => 'position_group/{position_group_id}/company_address',
	], function () {
		Route::get('', 'Api\V1\PositionGroupCompanyAddressController@get_addresses');
		Route::group([
			'prefix' => '/{company_address_id}',
		], function () {
			Route::get('', 'Api\V1\PositionGroupCompanyAddressController@get_address');
			Route::patch('', 'Api\V1\PositionGroupCompanyAddressController@set_address');
			Route::delete('', 'Api\V1\PositionGroupCompanyAddressController@unset_address');
		});
	});
	Route::resource('management_entity', 'Api\V1\ManagementEntityController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	Route::resource('contract_mode', 'Api\V1\ContractModeController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	Route::resource('contract_type', 'Api\V1\ContractTypeController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	Route::resource('retirement_reason', 'Api\V1\RetirementReasonController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	Route::resource('month', 'Api\V1\MonthController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	Route::group([
		'prefix' => 'role/{role_id}/permission',
	], function () {
		Route::get('', 'Api\V1\RolePermissionController@get_permissions');
		Route::group([
			'prefix' => '/{permission_id}',
		], function () {
			Route::get('', 'Api\V1\RolePermissionController@get_permission');
			Route::patch('', 'Api\V1\RolePermissionController@set_permission');
			Route::delete('', 'Api\V1\RolePermissionController@unset_permission');
		});
	});
	Route::resource('employer_contribution', 'Api\V1\EmployerContributionController')->except(['create', 'edit', 'update']);
});

Route::macro('admin_routes', function () {
	// Route::resource('user', 'Api\V1\UserController')->except(['store', 'create', 'edit']);
	Route::resource('user', 'Api\V1\UserController')->except(['store', 'create', 'edit', 'destroy']);
	Route::resource('user_action', 'Api\V1\UserActionController')->except(['create', 'store', 'edit', 'update']);
	Route::resource('role', 'Api\V1\RoleController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	Route::group([
		'prefix' => 'user/{user_id}/role',
	], function () {
		Route::get('', 'Api\V1\UserRoleController@get_roles');
		Route::group([
			'prefix' => '/{role_id}',
		], function () {
			Route::get('', 'Api\V1\UserRoleController@get_role');
			Route::post('', 'Api\V1\UserRoleController@set_role');
			Route::delete('', 'Api\V1\UserRoleController@unset_role');
		});
	});
});

Route::group([
	'middleware' => 'api',
	'prefix' => 'v1',
], function ($router) {
	Route::resource('auth', 'Api\V1\AuthController')->except(['index', 'create', 'edit']);
});

if (config('app.debug')) {
	Route::group([
		'middleware' => ['api'],
		'prefix' => 'v1',
	], function ($router) {
		Route::admin_routes();
		Route::common_routes();
		Route::general_routes();
	});
} else {
	Route::group([
		'middleware' => ['api', 'jwt.auth'],
		'prefix' => 'v1',
	], function ($router) {
		Route::group([
			'middleware' => ['role:admin'],
		], function ($r) {
			Route::admin_routes();
		});
		Route::common_routes();
		Route::general_routes();
	});
}
