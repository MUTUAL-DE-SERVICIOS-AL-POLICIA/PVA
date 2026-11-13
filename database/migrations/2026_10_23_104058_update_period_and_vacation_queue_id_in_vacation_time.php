<?php

//use Illuminate\Support\Facades\Schema;
//use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class UpdatePeriodAndVacationQueueIdInVacationTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function(){
            $rows = DB::table('vacation_time as vt')
            ->join('departures as d', 'd.id', '=', 'vt.departure_id')
            ->where('d.departure_reason_id',24)
            ->whereNull('d.deleted_at')
            ->whereNull('vt.deleted_at')
            ->whereRaw('d.approved IS DISTINCT FROM FALSE')
            ->select('vt.id','vt.date','vt.day','vt.period','vt.departure_id','vt.vacation_queue_id', 'vt.created_at','vt.updated_at','d.employee_id','d.departure','d.return')
            ->orderBy('vt.date')
            ->orderBy('vt.id')
            ->get();
            $queues = DB::table('vacation_queues')
            ->select( 'id','employee_id','end_date','max_date','days','rest_days','deleted_at')
            ->whereNull('deleted_at')
            ->orderBy('end_date')
            ->orderBy('id')
            ->get();
            $queueMap = [];
            $consumptionTargets=[];//para cuando de consume de la cola
            $assignedConsumption=[];//lo q se asigno a la cola
            foreach ($queues as $queue) {
                $queueId = (int) $queue->id;
                $queueMap[$queueId] = $queue;
                $target=round((float)$queue->days-(float)$queue->rest_days,2);
                $consumptionTargets[$queueId]=max(0,$target);//days-rest
                $assignedConsumption[$queueId]=0.00;
            }
            //para los registros de los consumos de las vacaciones
            $events=[];
            $sequence=0;
            foreach($rows as $row){
                $days = round((float) $row->day,2);
                $period=$this->normalizePeriod($row->period);
                if($days !== 0.50 && $days !==1.00){
                    continue;
                }
                // 1. si es day=1 se divide en en los turnos M y T
                if($days ===1.00 && $period === null){
                    $events[]=$this ->makeEvent(
                        $row,0.50, 'MAÑANA',true,'M', $sequence++
                    );
                    $events[]=$this->makeEvent(
                        $row, 0.50,  'TARDE', true, 'T', $sequence++
                    );
                    continue;
                }
                // reconocer medio turno si es mañana o tarde, apartir de 0.50
                if($days === 0.50){
                    if($period===null){
                        $period = $this->inferPeriod($row);
                    }
                    $events[]=$this->makeEvent( $row, 0.50, $period, false, null, $sequence++ );
                }
            }
            //dividir segun el orden de los consumos de las vacaciones
            usort($events, function($a,$b){
                $dateComparison = strcmp($a['date'],$b['date']);
                if($dateComparison !==0){
                    return $dateComparison;
                } 
                $order = [
                    'MAÑANA'=>0, 'TARDE'=>1,
                ];
                $periodA= isset($order[$a ['period']])?$order[$a['period']]:2;
                $periodB= isset($order[$b['period']])?$order[$b['period']]:2;
                if ($periodA !== $periodB){return $periodA <=> $periodB;}
                return $a['sequence']<=>$b['sequence'];
            });
            $plan = [];
            foreach ($events as $event) {
                $assignedQueueId = null;
                // Buscar a la cola más antigua 
                foreach ($queues as $queue) {
                    $queueId = (int) $queue->id;
                    // para no usar la cola de otro empleado.
                    if ((int) $queue->employee_id !== (int) $event['employee_id']) {
                        continue;
        }
        if (
            empty($queue->end_date) || empty($queue->max_date)
        ) {
            continue;
        }

        $endDate = substr($queue->end_date, 0, 10);
        $maxDate = substr($queue->max_date, 0, 10);

        if (
            $event['date'] < $endDate || $event['date'] > $maxDate
        ) {
            continue;
        }

        // No superar el consumo que corresponde a la cola del rest_days.
        $remainingTarget = round($consumptionTargets[$queueId] - $assignedConsumption[$queueId],2);

        if ($remainingTarget < $event['day']) {
            continue;
        }
        $assignedQueueId = $queueId;
        $assignedConsumption[$queueId] = round($assignedConsumption[$queueId] + $event['day'],2);
        break;
    }

    if ($assignedQueueId === null) {
        Log::warning(
            'Vacation time: no se encontró una cola disponible.',
            [
                'vacation_time_id' => $event['src_id'],'employee_id' => $event['employee_id'], 'date' => $event['date'],'day' => $event['day'],
            ]
        );
    }
    $event['assigned_queue_id'] = $assignedQueueId;
    $plan[] = $event;    
}      
            // vacation_time se dividiran el day, se asigana en id y period
            foreach ($plan as $event) {
                $changes = ['day' => $event['day'],'period' => $event['period'],'vacation_queue_id'=>$event['assigned_queue_id'],
                ];
                if ($event['split_part'] === 'M') {
                    DB::table('vacation_time')
                        ->where('id', $event['src_id'])
                        ->update($changes);
                    continue;
                }
                if ($event['split_part'] === 'T') {
                    $insert = [
                        'date' => $event['date'],
                        'day' => 0.50,
                        'period' => 'TARDE',
                        'departure_id' => $event['departure_id'],
                        'vacation_queue_id' =>
                            $event['assigned_queue_id'],
                        'created_at' => $event['created_at'],
                        'updated_at' => $event['updated_at'],
                    ];
                    DB::table('vacation_time')->insert($insert);
                    continue;
                }
                DB::table('vacation_time')
                    ->where('id', $event['src_id'])
                    ->update($changes);
            }
    });
}

    private function normalizePeriod($period)
    {
        if ($period === null || trim((string) $period) === '') { return null;}
        $period = strtoupper(trim((string) $period));
        if ($period === 'MAÑANA' || $period === 'TARDE') {return $period;}
        return null;
    }

    private function makeEvent($row,$day,$period,$split,$splitPart,$sequence)
    {
        return [
            'src_id' => (int) $row->id,
            'departure_id' => (int) $row->departure_id,
            'employee_id' => (int) $row->employee_id,
            'date' => substr($row->date, 0, 10),
            'day' => $day,
            'period' => $period,
            'split' => $split,
            'split_part' => $splitPart,
            'original_queue_id' =>
                $row->vacation_queue_id === null
                    ? null
                    : (int) $row->vacation_queue_id,
            'created_at' => $row->created_at,
            'updated_at' => $row->updated_at,
            'sequence' => $sequence,
        ];
    }
    private function inferPeriod($row)
    {
        if ( empty($row->departure) || empty($row->return)) {return null;
        }
        try {
        $departure = new \DateTime($row->departure);
        $return = new \DateTime($row->return);
        $date = new \DateTime($row->date);

        $departureDate = $departure->format('Y-m-d');
        $returnDate = $return->format('Y-m-d');
        $rowDate = $date->format('Y-m-d');

        $departureTime = $departure->format('H:i:s');
        $returnTime = $return->format('H:i:s');
        if (
            $departureDate === $returnDate && $rowDate === $departureDate
        ) {
            if ( $departureTime === '08:30:00' && $returnTime === '12:30:00'
            ) { return 'MAÑANA';
            }

            if ( $departureTime === '14:30:00' && $returnTime === '18:30:00'
            ) { return 'TARDE';
            }

            return null;
        }
        if (
            $rowDate === $departureDate && $departureDate < $returnDate
        ) {
            return $departureTime < '12:30:00' ? 'MAÑANA': 'TARDE';
        }
        if (
            $rowDate === $returnDate && $departureDate < $returnDate
        ) {
            return $returnTime <= '12:30:00' ? 'MAÑANA': 'TARDE';
        }
        return null;
        } catch (\Exception $exception) {
            \Illuminate\Support\Facades\Log::warning(
            'No se pudo inferir el turno de vacation_time.',
            [
                'vacation_time_id' => $row->id,
                'error' => $exception->getMessage(),
            ]
        );
        return null;
    }
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //no se revierte la migracion
    }
}
