<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigrateDaysOnVacationsToVacationTime extends Migration
{
    public function up()
    {
        $total = DB::table('days_on_vacations')->count();
        echo "Total a migrar: {$total}";

        if ($total === 0) {
            echo "days_on_vacations está vacía, nada que migrar.";
            return;
        }

        DB::table('days_on_vacations')
            ->orderBy('id')
            ->chunk(500, function ($rows) {
                $data = [];

                foreach ($rows as $row) {
                    $data[] = [
                        'id'                => $row->id,
                        'date'              => $row->date,
                        'day'               => $row->day,
                        'period'            => null,
                        'departure_id'      => $row->departure_id,
                        'vacation_queue_id' => null,
                        'created_at'        => $row->created_at,
                        'updated_at'        => $row->updated_at,
                        'deleted_at'        => null,
                    ];
                }
                DB::table('vacation_time')->insert($data);
            });
            DB::statement(
            "SELECT setval(pg_get_serial_sequence('vacation_time', 'id'), COALESCE((SELECT MAX(id) FROM vacation_time), 1))"
        );

        echo "Migradas: " . DB::table('vacation_time')->count() . " filas." . PHP_EOL;
    }

    public function down()
    {
        DB::table('vacation_time')->truncate();
    }
}