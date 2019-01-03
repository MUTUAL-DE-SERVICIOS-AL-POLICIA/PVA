<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Employee;
use App\Contract;
use App\Procedure;
use App\Payroll;
use App\ConsultantContract;
use App\ConsultantPosition;
use App\ConsultantProcedure;
use App\ConsultantPayroll;
use App\Observers\EmployeeObserver;
use App\Observers\ContractObserver;
use App\Observers\ProcedureObserver;
use App\Observers\PayrollObserver;
use App\Observers\ConsultantContractObserver;
use App\Observers\ConsultantPositionObserver;
use App\Observers\ConsultantProcedureObserver;
use App\Observers\ConsultantPayrollObserver;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    setlocale(LC_TIME, 'es_BO.utf8');
    Carbon::setLocale('es');

    // Observers
    Employee::observe(EmployeeObserver::class);
    Contract::observe(ContractObserver::class);
    Procedure::observe(ProcedureObserver::class);
    Payroll::observe(PayrollObserver::class);
    ConsultantContract::observe(ConsultantContractObserver::class);
    ConsultantPosition::observe(ConsultantPositionObserver::class);
    ConsultantProcedure::observe(ConsultantProcedureObserver::class);
    ConsultantPayroll::observe(ConsultantPayrollObserver::class);
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
