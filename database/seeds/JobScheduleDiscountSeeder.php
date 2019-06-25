<?php

use Illuminate\Database\Seeder;

class JobScheduleDiscountSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $discounts = [
      [
        'limit' => 2,
        'time' => 5,
        'unit' => 'minutes',
        'discount' => 0.5
      ], [
        'limit' => 0,
        'time' => 15,
        'unit' => 'minutes',
        'discount' => 1
      ], [
        'limit' => 0,
        'time' => 1,
        'unit' => 'days',
        'discount' => 1
      ]
    ];
    foreach ($discounts as $discount) {
      App\JobScheduleDiscount::create($discount);
    }
  }
}
