<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecalcularRestDaysVacationQueues extends Migration
{
    /**
     *recalcular los saldos de rest_days
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            //obtiene la cola de vacaciones sin deleted_at
            $colas = DB::table ('vacation_queues')
            ->whereNull('deleted_at')
            ->orderBy('employee_id')->orderBy('end_date')->orderBy('id')
            ->get();
            $colasPorEmpleado=[];
        foreach ($colas as $cola){
            $cola->saldo_calculado =(float)($cola->days ?? 0);
            $colasPorEmpleado[$cola->employee_id][]=$cola;
            }
        foreach ($colasPorEmpleado as $employeeId => $colasEmpleado){
             $vacaciones = DB::table('vacation_time as vt')
                    ->join('departures as d', 'd.id', '=', 'vt.departure_id')
                    ->where('d.employee_id', $employeeId)
                    ->where('d.departure_reason_id', 24)
                    ->whereRaw('d.approved =TRUE')
                    ->whereNull('d.deleted_at')
                    ->whereNull('vt.deleted_at')
                    ->where('vt.day', '>', 0)
                    ->select(
                        'vt.id',
                        'vt.date as fecha',
                        'vt.day as dias'
                    ) ->orderBy('vt.date')
                    ->orderBy('vt.id')
                    ->get();
            //descuenta los dias 
            foreach ($vacaciones as $vacacion){
                $pendiente=(float) $vacacion->dias;
                $fecha = substr((string)$vacacion->fecha,0 ,10);
                while ($pendiente > 0.00001){
                    $indiceElegible = null;
                    //busca a la cola mas antigua
                    foreach ($colasEmpleado as $indice => $cola){
                        if ($cola->saldo_calculado<= 0.00001){
                            continue;
                        }
                        $endDate=substr((string)$cola->end_date,0 ,10);
                        $maxDate = substr ((string)$cola->max_date,0 ,10);
                        if ( $fecha >= $endDate && $fecha<= $maxDate){
                            $indiceElegible=$indice;
                            break;
                        }
                    }
                    if ($indiceElegible === null){
                        break;
                    }
                    //descontar solo en el saldo disponeble
                    $saldoDisponible=$colasEmpleado[$indiceElegible]->saldo_calculado;
                    $asignar=min($pendiente,$saldoDisponible);
                    if( $asignar<=0){
                        break;
                    }
                    $colasEmpleado[$indiceElegible]->saldo_calculado -=$asignar;
                    $pendiente-=$asignar;
                }
            }
        }

        //execpcion solo para empleado =269
        if (isset($colasPorEmpleado[269])){
            foreach($colasPorEmpleado[269] as $cola){
                if((int)$cola->id===41){
                    $cola->saldo_calculado= 1.00;
                }
                if((int)$cola->id===129){
                    $cola->saldo_calculado= 6.00;
                }
            }
        }
        //actualizar el rest_days
        foreach($colasPorEmpleado as $colasEmpleado){
            foreach($colasEmpleado as $cola){
                DB::table('vacation_queues')
                ->where('id',$cola->id)
                ->whereNull('deleted_at')
                ->update(['rest_days'=>round($cola->saldo_calculado,2), 
            ]);
            }
        }

    });
}

    /**
     * Reverse the migrations. no se restaura automaticamente
     *
     * @return void
     */
    public function down()
    {
        //para revertir se necesita restaurar el backup de bd
    }
}
