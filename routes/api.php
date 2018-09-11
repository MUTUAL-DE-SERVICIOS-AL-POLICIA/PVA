<?php
Route::macro('rrhh_routes', function () {
	// Ticket
	Route::get('ticket/print/{id}', 'Api\V1\TicketController@print');
	// Employee
	Route::resource('employee', 'Api\V1\EmployeeController')->only(['index', 'show', 'store', 'update', 'destroy']);
	Route::get('employee/active/{active}', 'Api\V1\EmployeeController@filter_employees');
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
	// City
	Route::resource('city', 'Api\V1\CityController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	// Management Entity
	Route::resource('management_entity', 'Api\V1\ManagementEntityController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	// Insurance Company
	Route::resource('insurance_company', 'Api\V1\InsuranceCompanyController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	// Charge
	Route::resource('charge', 'Api\V1\ChargeController')->except(['create', 'edit']);
	// Position
	Route::resource('position', 'Api\V1\PositionController')->except(['create', 'edit']);
	// Payroll
	Route::get('payroll/getpayrollcontract/{contract_id}', 'Api\V1\PayrollController@getPayrollContract');
	Route::get('payroll/tributecalculation', 'Api\V1\PayrollController@tribute_calculation');
	Route::resource('payroll', 'Api\V1\PayrollController')->except(['create', 'edit']);
	Route::group([
		'prefix' => 'position/{superior_id}',
	], function () {
		Route::get('/dependency', 'Api\V1\DependencyPositionController@get_dependency');
		Route::get('/dependents', 'Api\V1\DependencyPositionController@get_dependents');
		Route::group([
			'prefix' => '/dependents/{dependent_id}',
		], function () {
			Route::get('', 'Api\V1\DependencyPositionController@get_dependent');
			Route::patch('', 'Api\V1\DependencyPositionController@set_dependent');
			Route::delete('', 'Api\V1\DependencyPositionController@unset_dependent');
		});
	});
	// Contract
	Route::get('contract/position_free/{position_id}', 'Api\V1\ContractController@positionFree');
	Route::resource('contract', 'Api\V1\ContractController')->only(['index', 'show', 'update', 'store', 'destroy']);
	// Job Schedule
	Route::resource('jobs_chedule', 'Api\V1\JobScheduleController')->except(['create', 'edit']);
	// Procedure
	Route::resource('procedure', 'Api\V1\ProcedureController')->except(['create', 'edit']);
	// Employer Number
	Route::resource('employer_number', 'Api\V1\EmployerNumberController')->except(['create', 'edit']);
	Route::group([
		'prefix' => 'employer_number/{employer_number_id}/insurance_company/{insurance_company_id}',
	], function () {
		Route::get('', 'Api\V1\EmployerNumberInsuranceCompanyController@get_insurance_company');
		Route::patch('', 'Api\V1\EmployerNumberInsuranceCompanyController@set_insurance_company');
	});
	// Company Account
	Route::resource('company_account', 'Api\V1\CompanyAccountController')->except(['create', 'edit']);
	// Company Address
	Route::resource('company_address', 'Api\V1\CompanyAddressController')->except(['create', 'edit']);
	Route::group([
		'prefix' => 'company_address/{company_address_id}/city/{city_id}',
	], function () {
		Route::get('', 'Api\V1\CompanyAddressCityController@get_city');
		Route::patch('', 'Api\V1\CompanyAddressCityController@set_city');
	});
	// Position Group
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
	// Contract Mode
	Route::resource('contract_mode', 'Api\V1\ContractModeController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	// Contract Type
	Route::resource('contract_type', 'Api\V1\ContractTypeController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	// Retirement Reason
	Route::resource('retirement_reason', 'Api\V1\RetirementReasonController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	// Month
	Route::resource('month', 'Api\V1\MonthController')->except(['store', 'create', 'edit', 'update', 'destroy']);
	// Employer COntribution
	Route::resource('employer_contribution', 'Api\V1\EmployerContributionController')->except(['create', 'edit']);
	// Employee Discount
	Route::resource('employee_discount', 'Api\V1\EmployeeDiscountController')->except(['create', 'edit']);
	// Procedure
	Route::group([
		'prefix' => 'procedure/year',
	], function () {
		Route::get('/list', 'Api\V1\ProcedureYearController@years');
		Route::get('/{year}', 'Api\V1\ProcedureYearController@with_year');
	});
	Route::group([
		'prefix' => 'procedure/{id}/payroll',
	], function () {
		Route::post('', 'Api\V1\ProcedurePayrollController@generate_payrolls');
		Route::get('', 'Api\V1\ProcedurePayrollController@get_payrolls');
	});
	Route::get('procedure/{id}/discounts', 'Api\V1\ProcedureController@discounts');
	// Payroll
	Route::get('payroll/print/pdf/{year}/{month}', 'Api\V1\PayrollPrintController@print_pdf')->name('print_pdf_payroll');
	Route::get('payroll/print/txt/{year}/{month}', 'Api\V1\PayrollPrintController@print_txt')->name('print_txt_payroll');
	Route::get('payroll/print/ovt/{year}/{month}', 'Api\V1\PayrollPrintController@print_ovt')->name('print_ovt_payroll');
});

Route::macro('admin_routes', function () {
	// User
	Route::resource('user', 'Api\V1\UserController')->except(['store', 'create', 'edit']);
	Route::resource('user_action', 'Api\V1\UserActionController')->except(['create', 'store', 'edit', 'update']);
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
	// Role
	Route::resource('role', 'Api\V1\RoleController')->except(['store', 'create', 'edit', 'update', 'destroy']);
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
	// Company
	Route::resource('company', 'Api\V1\CompanyController')->except(['create', 'edit', 'destroy']);
	// Document
	Route::resource('document', 'Api\V1\DocumentController')->except(['create', 'edit']);
	Route::resource('document_type', 'Api\V1\DocumentTypeController')->except(['create', 'edit']);
});

Route::macro('juridica_routes', function () {
	// Employee GET
	Route::resource('employee', 'Api\V1\EmployeeController')->only(['index', 'show']);
	// Contract GET and UPDATE
	Route::resource('contract', 'Api\V1\ContractController')->only(['index', 'show', 'update']);
});

Route::macro('common_routes', function () {
	// Login
	Route::resource('auth', 'Api\V1\AuthController')->except(['index', 'create', 'edit']);
	// Print Contract
	Route::get('contract/print/{id}/{type}', 'Api\V1\ContractController@print');
});

Route::group([
	'middleware' => 'api',
	'prefix' => 'v1',
], function ($router) {
	Route::common_routes();
});

if (config('app.debug')) {
	Route::group([
		'middleware' => ['api'],
		'prefix' => 'v1',
	], function ($router) {
		Route::admin_routes();
		Route::rrhh_routes();
	});
} else {
	Route::group([
		'middleware' => ['api', 'jwt.auth', 'role:admin'],
		'prefix' => 'v1',
	], function ($router) {
		Route::admin_routes();
	});
	Route::group([
		'middleware' => ['api', 'jwt.auth', 'role:admin|rrhh'],
		'prefix' => 'v1',
	], function ($r) {
		Route::rrhh_routes();
	});
	Route::group([
		'middleware' => ['api', 'jwt.auth', 'role:admin|rrhh|juridica'],
		'prefix' => 'v1',
	], function ($r) {
		Route::juridica_routes();
	});
}
