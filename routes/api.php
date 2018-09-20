<?php

Route::group([
	'middleware' => 'api',
	'prefix' => 'v1',
], function () {
	// Login
	Route::post('auth', 'Api\V1\AuthController@store')->name('login');
	Route::group([
		'middleware' => 'jwt.auth'
	], function () {
		// Logout and refresh token
		Route::get('auth', 'Api\V1\AuthController@show')->name('profile');
		Route::delete('auth', 'Api\V1\AuthController@destroy')->name('logout');
		Route::patch('auth', 'Api\V1\AuthController@update')->name('refresh');
		// Employee
		Route::get('employee', 'Api\V1\EmployeeController@index')->name('employees_list');
		Route::get('employee/{id}', 'Api\V1\EmployeeController@show')->name('employee_details');
		// Employee-Contract
		Route::group([
			'prefix' => 'employee/{employee_id}/contract',
		], function () {
			Route::get('', 'Api\V1\EmployeeContractController@get_contracts')->name('employee_contracts_list');
			Route::group([
				'prefix' => '/{contract_id}',
			], function () {
				Route::get('', 'Api\V1\EmployeeContractController@get_contract')->name('employee_contracts_details');
			});
		});
		// City
		Route::resource('city', 'Api\V1\CityController')->only(['index', 'show']);
		// Management Entity
		Route::resource('management_entity', 'Api\V1\ManagementEntityController')->only(['index', 'show']);
		// Insurance Company
		Route::resource('insurance_company', 'Api\V1\InsuranceCompanyController')->only(['index', 'show']);
		// Charge
		Route::get('charge', 'Api\V1\ChargeController@index')->name('charges_list');
		Route::get('charge/{id}', 'Api\V1\ChargeController@show')->name('charge_details');
		// Position
		Route::get('position', 'Api\V1\PositionController@index')->name('positions_list');
		Route::get('position/{id}', 'Api\V1\PositionController@show')->name('position_details');
		// Payroll
		Route::get('payroll', 'Api\V1\PayrollController@index')->name('payroll_list');
		Route::get('payroll/{id}', 'Api\V1\PayrollController@show')->name('payroll_details');
		Route::get('payroll/tributecalculation', 'Api\V1\PayrollController@tribute_calculation')->name('payroll_tribute_calculation');
		Route::get('payroll/print/pdf/{year}/{month}', 'Api\V1\PayrollPrintController@print_pdf')->name('print_pdf_payroll');
		Route::get('payroll/print/txt/{year}/{month}', 'Api\V1\PayrollPrintController@print_txt')->name('print_txt_payroll');
		Route::get('payroll/print/ovt/{year}/{month}', 'Api\V1\PayrollPrintController@print_ovt')->name('print_ovt_payroll');
		// Payroll-Contract
		Route::get('payroll/getpayrollcontract/{contract_id}', 'Api\V1\PayrollController@getPayrollContract')->name('payroll_contract');
		// Position
		Route::group([
			'prefix' => 'position/{superior_id}',
		], function () {
			Route::get('/dependency', 'Api\V1\DependencyPositionController@get_dependency')->name('position_dependency');
			Route::get('/dependents', 'Api\V1\DependencyPositionController@get_dependents')->name('position_dependents');
			Route::group([
				'prefix' => '/dependents/{dependent_id}',
			], function () {
				Route::get('', 'Api\V1\DependencyPositionController@get_dependent')->name('position_dependent_details');
			});
		});
		// Contract
		Route::get('contract', 'Api\V1\ContractController@index')->name('contracts_list');
		Route::get('contract/{id}', 'Api\V1\ContractController@show')->name('contract_details');
		Route::get('contract/position_free/{position_id}', 'Api\V1\ContractController@positionFree')->name('contract_position_free');
		Route::get('contract/print/{id}/{type}', 'Api\V1\ContractController@print')->name('contract_print');
		// Job Schedule
		Route::get('jobs_chedule', 'Api\V1\JobScheduleController@index')->name('jobs_chedule_list');
		Route::get('jobs_chedule/{id}', 'Api\V1\JobScheduleController@show')->name('jobs_chedule_details');
		// Procedure
		Route::get('procedure', 'Api\V1\ProcedureController@index')->name('procedure_list');
		Route::get('procedure/{id}', 'Api\V1\ProcedureController@show')->name('procedure_details');
		Route::get('procedure/date/{id}', 'Api\V1\ProcedureController@date')->name('procedure_dates');
		Route::get('procedure/order/{order}', 'Api\V1\ProcedureController@order')->name('procedure_last');
		Route::group([
			'prefix' => 'procedure/year',
		], function () {
			Route::get('/list', 'Api\V1\ProcedureYearController@years')->name('procedure_years');
			Route::get('/{year}', 'Api\V1\ProcedureYearController@with_year')->name('procedure_with_year');
		});
		Route::group([
			'prefix' => 'procedure/{id}/payroll',
		], function () {
			Route::get('', 'Api\V1\ProcedurePayrollController@get_payrolls')->name('procedure_payrolls');
		});
		Route::get('procedure/{id}/discounts', 'Api\V1\ProcedureController@discounts')->name('procedure_discounts');
		// Employer Number
		Route::get('employer_number', 'Api\V1\EmployerNumberController@index')->name('employer_number_list');
		Route::get('employer_number/{id}', 'Api\V1\EmployerNumberController@show')->name('employer_number_details');
		Route::group([
			'prefix' => 'employer_number/{employer_number_id}/insurance_company/{insurance_company_id}',
		], function () {
			Route::get('', 'Api\V1\EmployerNumberInsuranceCompanyController@get_insurance_company')->name('employer_number_insurance_company_details');
		});
		// Company Account
		Route::get('company_account', 'Api\V1\CompanyAccountController@index')->name('company_account_list');
		Route::get('company_account/{id}', 'Api\V1\CompanyAccountController@show')->name('company_account_details');
		// Company Address
		Route::get('company_address', 'Api\V1\CompanyAddressController@index')->name('company_address_list');
		Route::get('company_address/{id}', 'Api\V1\CompanyAddressController@show')->name('company_address_details');
		Route::group([
			'prefix' => 'company_address/{company_address_id}/city/{city_id}',
		], function () {
			Route::get('', 'Api\V1\CompanyAddressCityController@get_city')->name('company_address_get_city');
		});
		// Position Group
		Route::get('position_group', 'Api\V1\PositionGroupController@index')->name('position_group_list');
		Route::get('position_group/{id}', 'Api\V1\PositionGroupController@show')->name('position_group_details');
		Route::group([
			'prefix' => 'position_group/{superior_id}',
		], function () {
			Route::get('/dependency', 'Api\V1\DependencyPositionGroupController@get_dependency')->name('position_group_dependency');
			Route::get('/dependents', 'Api\V1\DependencyPositionGroupController@get_dependents')->name('position_group_dependents');
			Route::group([
				'prefix' => '/dependents/{dependent_id}',
			], function () {
				Route::get('', 'Api\V1\DependencyPositionGroupController@get_dependent')->name('position_group_dependent_details');
			});
		});
		Route::group([
			'prefix' => 'position_group/{position_group_id}/company_address',
		], function () {
			Route::get('', 'Api\V1\PositionGroupCompanyAddressController@get_addresses')->name('position_group_company_address_list');
			Route::group([
				'prefix' => '/{company_address_id}',
			], function () {
				Route::get('', 'Api\V1\PositionGroupCompanyAddressController@get_address')->name('position_group_company_address_details');
			});
		});
		// Contract Mode
		Route::resource('contract_mode', 'Api\V1\ContractModeController')->only(['index', 'show']);
		// Contract Type
		Route::resource('contract_type', 'Api\V1\ContractTypeController')->only(['index', 'show']);
		// Retirement Reason
		Route::resource('retirement_reason', 'Api\V1\RetirementReasonController')->only(['index', 'show']);
		// Month
		Route::resource('month', 'Api\V1\MonthController')->only(['index', 'show']);
		// Employer Contribution
		Route::get('employer_contribution', 'Api\V1\EmployerContributionController@index')->name('employer_contribution_list');
		Route::get('employer_contribution/{id}', 'Api\V1\EmployerContributionController@show')->name('employer_contribution_details');
		// Employee Discount
		Route::get('employee_discount', 'Api\V1\EmployeeDiscountController@index')->name('employee_discount_list');
		Route::get('employee_discount/{id}', 'Api\V1\EmployeeDiscountController@show')->name('employee_discount_details');
		// Company
		Route::get('company', 'Api\V1\CompanyController@index')->name('company_list');
		Route::get('company/{id}', 'Api\V1\CompanyController@show')->name('company_details');
		// Document
		Route::get('document', 'Api\V1\DocumentController@index')->name('document_list');
		Route::get('document/{id}', 'Api\V1\DocumentController@show')->name('document_details');
		Route::get('document_type', 'Api\V1\DocumentTypeController@index')->name('document_type_list');
		Route::get('document_type/{id}', 'Api\V1\DocumentTypeController@show')->name('document_type_details');

		// ADMIN routes
		Route::group([
			'middleware' => 'role:admin',
		], function () {
			// User
			Route::resource('user', 'Api\V1\UserController')->only(['index', 'store', 'show', 'update', 'delete']);
			// Role
			Route::get('role', 'Api\V1\RoleController@index')->name('roles_list');
			Route::get('role/{id}', 'Api\V1\RoleController@show')->name('role_details');
			Route::group([
				'prefix' => 'role/{role_id}/permission',
			], function () {
				Route::get('', 'Api\V1\RolePermissionController@get_permissions')->name('role_permissions_list');
				Route::group([
					'prefix' => '/{permission_id}',
				], function () {
					Route::get('', 'Api\V1\RolePermissionController@get_permission')->name('role_permission_details');
					Route::patch('', 'Api\V1\RolePermissionController@set_permission')->name('role_set_permission');
					Route::delete('', 'Api\V1\RolePermissionController@unset_permission')->name('role_unset_permission');
				});
			});
			// User-Role
			Route::group([
				'prefix' => 'user/{user_id}/role',
			], function () {
				Route::get('', 'Api\V1\UserRoleController@get_roles')->name('user_roles_list');
				Route::group([
					'prefix' => '/{role_id}',
				], function () {
					Route::get('', 'Api\V1\UserRoleController@get_role')->name('user_role_details');
					Route::patch('', 'Api\V1\UserRoleController@set_role')->name('user_set_role');
					Route::delete('', 'Api\V1\UserRoleController@unset_role')->name('user_unset_role');
				});
			});
			// User-Actions
			Route::resource('user_action', 'Api\V1\UserActionController')->only(['index', 'show', 'destroy']);
			// Company
			Route::post('company', 'Api\V1\CompanyController@store')->name('company_store');
			Route::patch('company/{id}', 'Api\V1\CompanyController@update')->name('company_update');
			// Document
			Route::post('document', 'Api\V1\DocumentController@store')->name('document_store');
			Route::patch('document/{id}', 'Api\V1\DocumentController@update')->name('document_update');
			Route::delete('document/{id}', 'Api\V1\DocumentController@delete')->name('document_delete');
			Route::post('document_type', 'Api\V1\DocumentTypeController@store')->name('document_type_store');
			Route::patch('document_type/{id}', 'Api\V1\DocumentTypeController@update')->name('document_type_update');
			Route::delete('document_type/{id}', 'Api\V1\DocumentTypeController@delete')->name('document_type_delete');
		});

		// RRHH routes
		Route::group([
			'middleware' => 'role:admin|rrhh',
		], function () {
			// Ticket
			Route::get('ticket/print/{id}', 'Api\V1\TicketController@print')->name('ticket_print');
			// Employee
			Route::get('employee/active/{active}', 'Api\V1\EmployeeController@filter_employees')->name('employee_active_list');
			Route::post('employee', 'Api\V1\EmployeeController@store')->name('employee_store');
			Route::patch('employee/{id}', 'Api\V1\EmployeeController@update')->name('employee_update');
			Route::delete('employee/{id}', 'Api\V1\EmployeeController@delete')->name('employee_delete');
			// Charge
			Route::post('charge', 'Api\V1\ChargeController@store')->name('charge_store');
			Route::patch('charge/{id}', 'Api\V1\ChargeController@update')->name('charge_update');
			Route::delete('charge/{id}', 'Api\V1\ChargeController@delete')->name('charge_delete');
			// Position
			Route::post('position', 'Api\V1\PositionController@store')->name('position_store');
			Route::patch('position/{id}', 'Api\V1\PositionController@update')->name('position_update');
			Route::delete('position/{id}', 'Api\V1\PositionController@delete')->name('position_delete');
			// Payroll
			Route::post('payroll', 'Api\V1\PayrollController@store')->name('payroll_store');
			Route::patch('payroll/{id}', 'Api\V1\PayrollController@update')->name('payroll_update');
			Route::delete('payroll/{id}', 'Api\V1\PayrollController@delete')->name('payroll_delete');
			// Position
			Route::group([
				'prefix' => 'position/{superior_id}',
			], function () {
				Route::group([
					'prefix' => '/dependents/{dependent_id}',
				], function () {
					Route::patch('', 'Api\V1\DependencyPositionController@set_dependent')->name('position_dependent_update');
					Route::delete('', 'Api\V1\DependencyPositionController@unset_dependent')->name('position_dependent_delete');
				});
			});
			// Contract
			Route::post('contract', 'Api\V1\ContractController@store')->name('contract_store');
			Route::delete('contract/{id}', 'Api\V1\ContractController@delete')->name('contract_delete');
			// Job Schedule
			Route::post('jobs_chedule', 'Api\V1\JobScheduleController@store')->name('jobs_chedule_store');
			Route::patch('jobs_chedule/{id}', 'Api\V1\JobScheduleController@update')->name('jobs_chedule_update');
			Route::delete('jobs_chedule/{id}', 'Api\V1\JobScheduleController@delete')->name('jobs_chedule_delete');
			// Procedure
			Route::post('procedure', 'Api\V1\ProcedureController@store')->name('procedure_store');
			Route::patch('procedure/{id}', 'Api\V1\ProcedureController@update')->name('procedure_update');
			Route::delete('procedure/{id}', 'Api\V1\ProcedureController@delete')->name('procedure_delete');
			Route::group([
				'prefix' => 'procedure/{id}/payroll',
			], function () {
				Route::post('', 'Api\V1\ProcedurePayrollController@generate_payrolls')->name('procedure_generate_payrolls');
			});
			// Employer Number
			Route::post('employer_number', 'Api\V1\EmployerNumberController@store')->name('employer_number_store');
			Route::patch('employer_number/{id}', 'Api\V1\EmployerNumberController@update')->name('employer_number_update');
			Route::delete('employer_number/{id}', 'Api\V1\EmployerNumberController@delete')->name('employer_number_delete');
			Route::group([
				'prefix' => 'employer_number/{employer_number_id}/insurance_company/{insurance_company_id}',
			], function () {
				Route::patch('', 'Api\V1\EmployerNumberInsuranceCompanyController@set_insurance_company')->name('employer_number_insurance_company_update');
			});
			// Company Account
			Route::post('company_account', 'Api\V1\CompanyAccountController@store')->name('company_account_store');
			Route::patch('company_account/{id}', 'Api\V1\CompanyAccountController@update')->name('company_account_update');
			Route::delete('company_account/{id}', 'Api\V1\CompanyAccountController@delete')->name('company_account_delete');
			// Company Address
			Route::post('company_address', 'Api\V1\CompanyAddressController@store')->name('company_address_store');
			Route::patch('company_address/{id}', 'Api\V1\CompanyAddressController@update')->name('company_address_update');
			Route::delete('company_address/{id}', 'Api\V1\CompanyAddressController@delete')->name('company_address_delete');
			Route::group([
				'prefix' => 'company_address/{company_address_id}/city/{city_id}',
			], function () {
				Route::patch('', 'Api\V1\CompanyAddressCityController@set_city')->name('company_address_set_city');
			});
			// Position Group
			Route::post('position_group', 'Api\V1\PositionGroupController@store')->name('position_group_store');
			Route::patch('position_group/{id}', 'Api\V1\PositionGroupController@update')->name('position_group_update');
			Route::delete('position_group/{id}', 'Api\V1\PositionGroupController@delete')->name('position_group_delete');
			Route::group([
				'prefix' => 'position_group/{superior_id}',
			], function () {
				Route::group([
					'prefix' => '/dependents/{dependent_id}',
				], function () {
					Route::patch('', 'Api\V1\DependencyPositionGroupController@set_dependent')->name('position_group_set_dependent');
					Route::delete('', 'Api\V1\DependencyPositionGroupController@unset_dependent')->name('position_group_unset_dependent');
				});
			});
			Route::group([
				'prefix' => 'position_group/{position_group_id}/company_address',
			], function () {
				Route::group([
					'prefix' => '/{company_address_id}',
				], function () {
					Route::patch('', 'Api\V1\PositionGroupCompanyAddressController@set_address')->name('position_group_set_address');
					Route::delete('', 'Api\V1\PositionGroupCompanyAddressController@unset_address')->name('position_group_unset_address');
				});
			});
			// Employer Contribution
			Route::post('employer_contribution', 'Api\V1\EmployerContributionController@store')->name('employer_contribution_store');
			Route::patch('employer_contribution/{id}', 'Api\V1\EmployerContributionController@update')->name('employer_contribution_update');
			Route::delete('employer_contribution/{id}', 'Api\V1\EmployerContributionController@delete')->name('employer_contribution_delete');
			// Employee Discount
			Route::post('employee_discount', 'Api\V1\EmployeeDiscountController@store')->name('employee_discount_store');
			Route::patch('employee_discount/{id}', 'Api\V1\EmployeeDiscountController@update')->name('employee_discount_update');
			Route::delete('employee_discount/{id}', 'Api\V1\EmployeeDiscountController@delete')->name('employee_discount_delete');
		});

		// JURIDICA-RRHH routes
		Route::group([
			'middleware' => 'role:admin|rrhh|juridica',
		], function () {
			Route::patch('contract/{id}', 'Api\V1\ContractController@update')->name('contract_update');
		});
	});
});