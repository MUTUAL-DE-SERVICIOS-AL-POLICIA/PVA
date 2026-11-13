<?php

namespace App\Http\Controllers\Api\V1;

use App\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VacationQueue;
use App\Vacation;
use App\Contract;
use App\VacationTime;
use App\Departure;
use App\DepartureReason;
use Carbon\Carbon;
use DB;

class VacationQueueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return VacationQueue::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vacation_queue = VacationQueue::create($request->all());
        return $vacation_queue;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return VacationQueue::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vacation_queue = VacationQueue::findOrFail($id);
        if ($this->queueHasDepartures($id)) {
            return response()->json(['message' => self::MSG_QUEUE_LINKED], 422);
        }
        $vacation_queue->fill($request->all());
        $vacation_queue->save();
        return $vacation_queue;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    const MSG_QUEUE_LINKED = 'No se puede eliminar, ni editar porque tiene permisos de vacaciones registrado a esta cola';
    private function queueHasDepartures($queue_id)
    {
        return DB::table('vacation_time as vt')
        ->join('departures as d', 'd.id', '=', 'vt.departure_id')
        ->where('vt.vacation_queue_id', $queue_id)
        ->whereNull('vt.deleted_at')
        ->whereNull('d.deleted_at')
        ->whereRaw('d.approved IS DISTINCT FROM FALSE')
        ->exists();
        }
    public function destroy(Request $request, $id)
    {
        $vacation_queue = VacationQueue::findOrFail($id);
        if ($this->queueHasDepartures($id)) {
            return response()->json(['message' => self::MSG_QUEUE_LINKED], 422);
        }
    
        // Guardar el comentario antes de eliminar
        $vacation_queue->comment = $request->input('comment');
        $vacation_queue->save();
    
        // Eliminar lógicamente
        $vacation_queue->delete();
    
        return response()->json([
            'message' => 'Registro eliminado lógicamente con comentario',
            'data' => $vacation_queue
        ]);
    }

    public function count_days(Request $request)
    {
      $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'date' => 'required|date'
      ]);
      $employee_id = $request->input('employee_id');
      $date = $request->input('date');
      $count = Employee::findOrFail($employee_id)
            ->vacation_queues()
            ->where('max_date', '>=', Carbon::parse($date)->format('Y-m-d'))
            ->where('is_valid', true)
            ->sum('rest_days');
      $occupied = DB::table('vacation_time as vt')
            ->join('departures as d', 'd.id', '=', 'vt.departure_id')
            ->where('d.employee_id', $employee_id)
            ->where('d.departure_reason_id', 24)
            ->whereNull('d.deleted_at')
            ->whereNull('vt.deleted_at')
            ->whereDate('vt.date', '>=', $date)
            ->select('vt.date','vt.day','vt.period','d.departure', 'd.return')
            ->orderBy('vt.date')
            ->get();
        $days = [];
     foreach ($occupied as $item) {$key = Carbon::parse($item->date)->format('Y-m-d');
     if (!isset($days[$key])) {
        $days[$key] = [
            'date' => $key,
            'day' => 0,
            'morning' => false,
            'afternoon' => false,
        ];
    }
    $days[$key]['day'] += (float) $item->day;

    // Los registros nuevos identifican directamente el turno.
    if ($item->period === 'MAÑANA')
    {
        $days[$key]['morning'] = true;
    } elseif ($item->period === 'TARDE') 
    {
        $days[$key]['afternoon'] = true;
    } elseif ((float) $item->day >= 1) 
    {
        $days[$key]['morning'] = true;
        $days[$key]['afternoon'] = true;
    } else 
    {
        $departureDate = Carbon::parse($item->departure)->format('Y-m-d');
        $returnDate = Carbon::parse($item->return)->format('Y-m-d');
        $currentDate = $key;
        if ($currentDate === $departureDate) 
            {
                $hour = Carbon::parse($item->departure)->format('H:i');
                if ($hour === '08:30') 
                    {
                        $days[$key]['morning'] = true;
                    } elseif ($hour === '14:30') 
                    {
                        $days[$key]['afternoon'] = true;
                    }
            } elseif ($currentDate === $returnDate) 
            {
                $hour = Carbon::parse($item->return)->format('H:i');
                if ($hour === '12:30') {
                        $days[$key]['morning'] = true;
                    } elseif ($hour === '18:30') {
                        $days[$key]['afternoon'] = true;
                    }
            } else 
        {
            $days[$key]['morning'] = true;
            $days[$key]['afternoon'] = true;
        }
    }
}

$days = array_values($days);   
      /*$daysV = DaysOnVacation::where('date','>=', $date)->pluck('departure_id');
      $departures = Departure::where('employee_id',$employee_id)->where('departure_reason_id', 24)->whereIn('id',$daysV)->get();
      $days = array();
      foreach($departures as $departure)
      {
        foreach($departure->days_on_vacation as $days_on_vacation)
        {
          $morning = true;
          $afternoon = true;
          if($days_on_vacation->date == Carbon::parse($days_on_vacation->departure->departure)->format('Y-m-d') && $days_on_vacation->day == 0.5)
          {
            if(Carbon::parse($departure->departure)->format('H:i') == '08:30')
              $afternoon = false;
            elseif(Carbon::parse($departure->departure)->format('H:i') == '14:30')
              $morning = false;
          }
          elseif($days_on_vacation->date == Carbon::parse($days_on_vacation->departure->return)->format('Y-m-d') && $days_on_vacation->day == 0.5)
          {
            if(Carbon::parse($departure->return)->format('H:i') == '12:30')
              $afternoon = false;
            elseif(Carbon::parse($departure->return)->format('H:i') == '18:30')
              $morning = false;
          }
          array_push($days, [
            'date' => $days_on_vacation->date,
            'day' => $days_on_vacation->day,
            'morning' => $morning,
            'afternoon' => $afternoon
          ]);
        }
      }*/
      return response()->json([
        'count' => max($count, 0),
        'days' => $days,
      ]);
    }

  public function queue_vacation()
  {
      DB::beginTransaction();
      try {
          $today = Carbon::today();

          $contracts = Contract::with(['employee.get_cas'])
              ->where('active', true)
              ->get();

          foreach ($contracts as $contract) {
              $employee = $contract->employee;
              if (!$employee || empty($employee->addmission_date)) {
                  continue;
              }

              $admission = Carbon::parse($employee->addmission_date);
              $years_of_service = $admission->diffInYears($today);
              if ($years_of_service < 1) {
                  continue;
              }

              $latest_anniversary = $admission->copy()->addYears($years_of_service);
              if (!$today->isSameDay($latest_anniversary->copy()->addDay())) {
                  continue;
              }

              $period_end = $today->copy()->subDay();

              $already_queued = VacationQueue::where('employee_id', $employee->id)
                  ->whereDate('end_date', $period_end)
                  ->exists();

              if ($already_queued) {
                  continue;
              }

              $cas = $employee->get_cas
                  ->where('active', true)
                  ->first();

              if ($cas) {
                  if ($cas->days == 0 && $cas->months == 0 && $cas->years > 0) {
                      $days = Vacation::where('from', '<', $cas->years)
                          ->where('active', true)
                          ->orderByDesc('id')
                          ->first()->days ?? 15;
                  } elseif ($cas->years > 0) {
                      $days = Vacation::where('from', '<=', $cas->years)
                          ->where('active', true)
                          ->orderByDesc('id')
                          ->first()->days ?? 15;
                  } else {
                      $days = Vacation::where('active', true)
                          ->orderByDesc('id')
                          ->first()->days ?? 15;
                  }
              } else {
                  $days = Vacation::where('active', true)
                      ->orderBy('id', 'asc')
                      ->first()->days ?? 15;
              }

              // Solo silencia eventos para esta creación 
              VacationQueue::withoutEvents(function () use ($today, $period_end, $days, $employee) {
                  VacationQueue::create([
                      'start_date'  => $today->copy()->subYear()->subDay(),
                      'end_date'    => $period_end,
                      'days'        => $days,
                      'rest_days'   => $days,
                      'max_date'    => $today->copy()->addYears(2)->toDateString(),
                      'employee_id' => $employee->id,
                  ]);
              });
          }
          VacationQueue::where('max_date', '<', $today->toDateString())
              ->where('is_valid', true)
              ->update(['is_valid' => false]);

          DB::commit();
      } catch (\Exception $e) {
          DB::rollBack();
          return $e;
      }
  }

    // calcula la cantidad de dias restantes para vacaciones
    public function sum_rest_days(Request $request)
    {
      $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'date' => 'required|date'
      ]);
      $count = 0;
      $employee_id = $request->input('employee_id');
      $date = $request->input('date');
      $count = Employee::findOrFail($employee_id)
              ->vacation_queues()
              ->where('max_date', '>=', Carbon::parse($date)->format('Y-m-d'))
              ->where('is_valid', true)
              ->sum('rest_days');
      return $count;
    }

    public function get_vacation_queue_employee($employee_id)
    {
        $queues = VacationQueue::where('employee_id', $employee_id)
            ->orderBy('start_date', 'asc')
            ->get();
        $queues->each(function ($queue) {
            $queue->has_departures = $this->queueHasDepartures($queue->id);
        });
      return $queues;
    }
    public function destroy_with_comment(Request $request, $id)
    {
        $vacation_queue = VacationQueue::findOrFail($id);
        if ($this->queueHasDepartures($id)) {
            return response()->json(['message' => self::MSG_QUEUE_LINKED], 422);
        }

        // Guardar comentario antes de eliminar
        $vacation_queue->comment = $request->input('comment');
        $vacation_queue->save();

        // Soft delete
        $vacation_queue->delete();

        return response()->json([
            'message' => 'Registro eliminado lógicamente con comentario',
            'data' => $vacation_queue
        ]);
    }

    public function print_cancelled_report(Request $request)
    {
      $num = 0; 
      $formatted_date = Carbon::now()->format('d-m-Y');

      $vacation_queues = VacationQueue::onlyTrashed()
                  ->orderBy('employee_id', 'asc')
                  ->get();

      $data = [
        'num' => $num,
        'vacation_queues' => $vacation_queues
      ];
      $file_name = implode('_', ['reporte', 'anulados', 'cola','vacaciones', $formatted_date]) . '.pdf';

      $options = [
        'orientation' => 'landscape',
        'page-width' => '216',
        'page-height' => '279',
        'margin-top' => '10',
        'margin-bottom' => '10',
        'margin-left' => '10',
        'margin-right' => '10',
        'encoding' => 'UTF-8',
      ];

      $pdf = \PDF::loadView('vacation.report_cancelled_vacation_queue', $data);
      $pdf->setOptions($options);

      return $pdf->stream($file_name);
    }

}
