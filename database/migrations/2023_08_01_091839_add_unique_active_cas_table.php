<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddUniqueActiveCasTable extends Migration
{
  public function up()
  {
      DB::unprepared('
          CREATE OR REPLACE FUNCTION trgr_update_activos()
          RETURNS TRIGGER AS $$
          BEGIN
              IF NEW.active THEN
                  UPDATE cas_certifications
                  SET active = false
                  WHERE employee_id = NEW.employee_id AND id <> NEW.id;
              END IF;
              RETURN NEW;
          END;
          $$ LANGUAGE plpgsql;

          CREATE TRIGGER trigger_update_activos
          BEFORE INSERT OR UPDATE ON cas_certifications
          FOR EACH ROW
          EXECUTE FUNCTION trgr_update_activos();
      ');
  }

  public function down()
  {
      DB::unprepared('
          DROP TRIGGER IF EXISTS trigger_update_activos ON cas_certifications;
          DROP FUNCTION IF EXISTS trgr_update_activos();
      ');
  }
}
