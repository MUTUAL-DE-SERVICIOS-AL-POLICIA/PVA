<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseProductionSeeder extends Seeder
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

    $role = App\Role::create([
      'name' => 'admin',
      'display_name' => 'Administrador',
      'description' => 'Rol administrador',
    ]);

    $user = App\User::create([
      'username' => 'admin',
      'password' => bcrypt('admin'),
    ]);

    $user->attachRole($role);

    $this->call(CitySeeder::class);
    $this->call(InsuranceCompanySeeder::class);
    $this->call(ManagementEntitySeeder::class);
    $this->call(RetirementReasonSeeder::class);
    $this->call(ContractTypeSeeder::class);
    $this->call(ContractModeSeeder::class);
    $this->call(MonthSeeder::class);
    $this->call(DepartureGroupSeeder::class);
    $this->call(DepartureReasonSeeder::class);
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
