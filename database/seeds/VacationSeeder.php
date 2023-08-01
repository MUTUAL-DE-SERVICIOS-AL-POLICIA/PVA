<?php

use Illuminate\Database\Seeder;

class VacationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vacations = [
            ['from' => 1, 'to' => 5, 'days' => 15, 'active' => true],
            ['from' => 5, 'to' => 10, 'days' => 20, 'active' => true],
            ['from' => 10, 'to' => 100, 'days' => 30, 'active' => true],
        ];

        foreach ($vacations as $vacation) {
            App\Vacation::create($vacation);
        }
    }
}
