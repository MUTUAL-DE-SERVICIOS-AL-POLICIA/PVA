<?php

use Illuminate\Database\Seeder;

class SeniorityBonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ranges = [
            ['from' => 2, 'to' => 4, 'percentage' => 0.05, 'active' => true],
            ['from' => 5, 'to' => 7, 'percentage' => 0.11, 'active' => true],
            ['from' => 8, 'to' => 10, 'percentage' => 0.18, 'active' => true],
            ['from' => 11, 'to' => 14, 'percentage' => 0.26, 'active' => true],
            ['from' => 15, 'to' => 19, 'percentage' => 0.34, 'active' => true],
            ['from' => 20, 'to' => 24, 'percentage' => 0.42, 'active' => true],
            ['from' => 25, 'to' => 100, 'percentage' => 0.50, 'active' => true],
        ];

        foreach ($ranges as $range) {
            App\SeniorityBonus::create($range);
        }
    }
}
