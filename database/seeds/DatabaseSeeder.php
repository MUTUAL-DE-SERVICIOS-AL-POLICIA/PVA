<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
	 * Seed the application's database.
	 *
	 * @return void
	 */
  public function run()
  {
    $this->command->info('Unguarding models');
    Model::unguard();

    $tables = [
      'charges',
      'cities',
      'companies',
      'company_accounts',
      'company_addresses',
      'contract_types',
      'contract_modes',
      'contracts',
      'dependency_position_group',
      'dependency_positions',
      'employees',
      'employer_numbers',
      'insurance_companies',
      'job_schedules',
      'management_entities',
      'permission_role',
      'permission_user',
      'permissions',
      'position_groups',
      'positions',
      'retirement_reasons',
      'role_user',
      'roles',
      'users',
      'employee_discounts',
      'employer_contributions',
      'minimum_salaries',
      'months',
      'procedures',
      'payrolls',
      'consultant_procedures',
      'consultant_payrolls',
      'user_actions',
      'departures',
      'departure_reasons',
      'departure_groups'
    ];

    $this->command->info('Truncating existing tables');
    foreach ($tables as $table) {
      DB::statement('TRUNCATE TABLE ' . $table . ' CASCADE;');
    }

    $this->call(UserSeeder::class);
    Auth::login(App\User::first());
    $this->call(CitySeeder::class);
    $this->call(InsuranceCompanySeeder::class);
    $this->call(ManagementEntitySeeder::class);
    $this->call(DocumentTypeSeeder::class);
    $this->call(DocumentSeeder::class);
    $this->call(CompanySeeder::class);
    $this->call(CompanyAddressSeeder::class);
    $this->call(PositionGroupSeeder::class);
    $this->call(ChargeSeeder::class);
    $this->call(PositionSeeder::class);
    $this->call(ConsultantPositionSeeder::class);
    $this->call(EmployeeSeeder::class);
    $this->call(JobScheduleSeeder::class);
    $this->call(RetirementReasonSeeder::class);
    $this->call(ContractTypeSeeder::class);
    $this->call(ContractModeSeeder::class);
    $this->call(ContractSeeder::class);
    $this->call(ConsultantContractSeeder::class);
    $this->call(EmployeeDiscountSeeder::class);
    $this->call(EmployerContributionSeeder::class);
    $this->call(MinimumSalarySeeder::class);
    $this->call(MonthSeeder::class);
    $this->call(ProcedureSeeder::class);
    $this->call(PayrollSeeder::class);
    $this->call(ConsultantProcedureSeeder::class);
    $this->call(ConsultantPayrollSeeder::class);
    $this->call(UserActionSeeder::class);
    $this->call(DocumentTypeAdd2Seeder::class);
    $this->call(DepartureGroupSeeder::class);
    $this->call(DepartureReasonSeeder::class);
    $this->call(ContractTypeDepartureReasonSeeder::class);
    $this->call(UserActionPermissionSeeder::class);
    $this->call(SupplyPermissionSeeder::class);
    $this->call(ProcedureEventualPermissionSeeder::class);
    $this->call(ProcedureConsultantPermissionSeeder::class);
    $this->call(EventualPermissionSeeder::class);
    $this->call(EmployeePermissionSeeder::class);
    $this->call(DeparturePermissionSeeder::class);
    $this->call(ConsultantPermissionSeeder::class);
    $this->call(AdminPermissionSeeder::class);
  }
}
