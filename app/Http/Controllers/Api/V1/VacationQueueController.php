<?php

namespace App\Http\Controllers\Api\V1;

use App\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VacationQueue;
use App\Vacation;
use App\Contract;
use App\DaysOnVacation;
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
    public function destroy($id)
    {
        $vacation_queue = VacationQueue::findOrFail($id);
        $vacation_queue->delete();
        return $vacation_queue;
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
            ->sum('rest_days');
      $daysV = DaysOnVacation::where('date','>=', $date)->pluck('departure_id');
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
      }
      return response()->json([
        'count' => max($count, 0),
        'days' => $days,
      ]);
    }

    public function queue_vacation()
    {
      DB::beginTransaction();
      try
      {
        $employees_contracts = Contract::where('active', true)->get();
        foreach($employees_contracts as $employee_contract)
        {
          if(VacationQueue::where('employee_id', $employee_contract->employee_id)->whereYear('end_date', Carbon::now()->year)->count() == 0)
          {
            if(Carbon::parse($employee_contract->employee->addmission_date)->addDay()->format('m-d')  == Carbon::now()->format('m-d'))
            {
              $cas = $employee_contract->employee->get_cas->where('active', true)->where('for_vacation', true)->first();
              if($cas)
              {
                if($cas->days == 0 && $cas->months == 0 && $cas->years > 0)
                  $days = Vacation::where('from','<', $cas->years)->where('active', true)->orderby('id', 'desc')->first()->days;
                elseif($cas->days > 0 && $cas->years > 0 || $cas->months > 0 && $cas->years > 0)
                  $days = Vacation::where('from','<=', $cas->years)->where('active', true)->orderBy('id', 'desc')->first()->days;
                elseif($cas->years == 0)
                  $days = Vacation::where('active', true)->orderBy('id', 'desc')->first()->days;
              }
              else{
                $days = Vacation::where('active', true)->orderBy('id', 'asc')->first()->days;
              }
              $queue_vacation = Vacationqueue::create([
                'start_date' => Carbon::now()->subYear()->subDay(),
                'end_date' => Carbon::now()->subDay(),
                'days' => $days,
                'rest_days' => $days,
                'max_date' => Carbon::now()->addYear(2)->format('Y-m-d'),
                'employee_id' => $employee_contract->employee_id,
              ]);
            }
          }
        }
        DB::commit();
      }catch (\Exception $e) {
        DB::rollback();
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
              ->sum('rest_days');
      return $count;
    }

}
