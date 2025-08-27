<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateJobSchedulesToContracts extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(): void
  {
    DB::transaction(function () {
      DB::statement(<<<'SQL'
        INSERT INTO public.job_schedules
        (start_hour, start_minutes, end_hour, end_minutes, workdays, created_at, updated_at, start_hour_min_limit, start_minutes_min_limit, end_hour_max_limit, end_minutes_max_limit)
        VALUES (8, 30, 18, 30, '[1,2,3,4,5]'::json, now(), now(), 4, 0, 23, 55);
        SQL);

      DB::statement(<<<'SQL'
        WITH agg AS (
          SELECT
            cjs.contract_id,
            COUNT(DISTINCT cjs.job_schedule_id) AS distinct_cnt,
            MIN(cjs.job_schedule_id)           AS single_id
          FROM contract_job_schedule cjs
          WHERE cjs.contract_id IS NOT NULL
          GROUP BY cjs.contract_id
        )
        UPDATE contracts c
        SET job_schedule_id = CASE
          WHEN agg.distinct_cnt > 1 THEN 6
          WHEN agg.distinct_cnt = 1 THEN agg.single_id
          ELSE c.job_schedule_id
        END
        FROM agg
        WHERE c.id = agg.contract_id;
        SQL);

      DB::statement(<<<'SQL'
        WITH agg AS (
          SELECT
            cjs.consultant_contract_id,
            COUNT(DISTINCT cjs.job_schedule_id) AS distinct_cnt,
            MIN(cjs.job_schedule_id)           AS single_id
          FROM contract_job_schedule cjs
          WHERE cjs.consultant_contract_id IS NOT NULL
          GROUP BY cjs.consultant_contract_id
        )
        UPDATE consultant_contracts c
        SET job_schedule_id = CASE
          WHEN agg.distinct_cnt > 1 THEN 6
          WHEN agg.distinct_cnt = 1 THEN agg.single_id
          ELSE c.job_schedule_id
        END
        FROM agg
        WHERE c.id = agg.consultant_contract_id;
        SQL);

        Schema::dropIfExists('contract_job_schedule');
    });
  }

  public function down(): void
  {
    DB::transaction(function () {
      DB::statement(<<<'SQL'
      UPDATE contracts SET job_schedule_id = NULL WHERE job_schedule_id = 6;
      SQL);
                  DB::statement(<<<'SQL'
      UPDATE consultant_contracts SET job_schedule_id = NULL WHERE job_schedule_id = 6;
      SQL);

                  // Eliminar el registro insertado si coincide exactamente con los valores (evita borrar otro distinto)
                  DB::statement(<<<'SQL'
      DELETE FROM public.job_schedules
      WHERE start_hour = 8
        AND start_minutes = 30
        AND end_hour = 18
        AND end_minutes = 30
        AND workdays = '[1,2,3,4,5]'::json
        AND start_hour_min_limit = 4
        AND start_minutes_min_limit = 0
        AND end_hour_max_limit = 23
        AND end_minutes_max_limit = 55;
      SQL);
    });
  }
}
