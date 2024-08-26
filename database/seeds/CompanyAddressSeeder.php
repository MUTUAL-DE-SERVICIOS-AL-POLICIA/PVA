<?php

use Illuminate\Database\Seeder;

class CompanyAddressSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(App\CompanyAddress::class, 11)->create();
  }
}
