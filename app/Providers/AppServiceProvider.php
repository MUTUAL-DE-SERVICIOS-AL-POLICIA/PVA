<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Employee;
use App\Contract;
use App\Observers\EmployeeObserver;
use App\Observers\ContractObserver;

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
    Employee::observe(EmployeeObserver::class);
    Contract::observe(ContractObserver::class);
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
