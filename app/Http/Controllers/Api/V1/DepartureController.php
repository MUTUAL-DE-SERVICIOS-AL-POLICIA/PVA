<?php

namespace App\Http\Controllers\Api\V1;

use App\Contract;
use App\Departure;
use App\PositionGroup;
use App\Employee;
use App\VacationQueue;
use App\DepartureReason;
use App\EmployeeDeparture;
use App\DaysOnVacation;
use App\Http\Requests\DepartureForm;
use App\Http\Controllers\Controller;
use Carbon;
use DB;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

/** @resource Departure
 *
 * Resource to retrieve, store and update departure data
 */
class DepartureController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $date = (object)[
      'from' => $request['from'],
      'to' => $request['to']
    ];

    $query = Departure::join('departure_reasons', 'departures.departure_reason_id', '=', 'departure_reasons.id')->select('departures.*', 'departure_reasons.description_needed', 'departure_reasons.note');

    if ($request->has('approved')) {
      if ($request->approved != 'all') {
        $query = $query->whereApproved(json_decode($request->approved));
      }
    }

    $query = $query->where(function ($subQuery) use ($date) {
      $subQuery->where(function ($q) use ($date) {
        return $q->whereDate('departure', '>=', $date->from)->whereDate('departure', '<=', $date->to);
      })->orWhere(function ($q) use ($date) {
        return $q->whereDate('return', '>=', $date->from)->whereDate('return', '<=', $date->to);
      });
    });

    $employee_id = intval($request['employee_id']);
    if ($employee_id) {
      $query = $query->where('employee_id', $employee_id);
    } else {
      $query = $query->join('employees', 'departures.employee_id', '=', 'employees.id')->select('departures.*', 'departure_reasons.description_needed', 'departure_reasons.note', 'employees.last_name', 'employees.mothers_last_name', 'employees.first_name', 'employees.second_name')->orderBy('employees.last_name', 'ASC');
    }

    $departures = $query->orderBy('departures.departure', 'ASC')->orderBy('departures.return', 'ASC')->get();

    if ($request->has('type')) {
      $filtered = [];
      foreach ($departures as $departure) {
        $is_consultant = $departure->employee->consultant();
        unset($departure->employee);
        if (($request['type'] == 'consultant' && !$is_consultant) || ($request['type'] == 'eventual' && $is_consultant)) {
          $departures = $departures->except($departure->id);
        } else {
          $filtered[] = $departure;
        }
      }

      if ($request['type'] == 'consultant') {
        $departures = $filtered;
      }
    }

    return $departures;
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(DepartureForm $request)
  {
    $lastCode = Departure::latest()->first()->code;
    $newCode = $lastCode + 1;
    $departure = Departure::create(array_merge($request->all(), [
        'code' => $newCode,
    ]));

    return $departure;
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    return Departure::with('employee')->join('departure_reasons', 'departures.departure_reason_id', '=', 'departure_reasons.id')->select('departures.*', 'departure_reasons.description_needed', 'departure_reasons.note')->orderBy('departures.created_at')->findOrFail($id);
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
    $departure = Departure::findOrFail($id);
    $departure->fill($request->all());
    $departure->save();
    return $departure;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $departure = Departure::findOrFail($id);
    if ($departure->approved === null && $departure->departure_reason->name != 'VACACIONES') $departure->delete();
    return $departure;
  }

  /**
   * PDF the specified resource from storage.
   *
   * @param  int  $id
   * @return pdf
   */
  public function print($id)
  {
    $data = array('departure' => $departure = Departure::with(['departure_reason' => function ($query) {
      return $query->with('departure_group');
    }])->findOrFail($id));

    $file_name = implode('_', ['solicitud', 'salida', $data['departure']->employee->first_name, $data['departure']->employee->last_name, $departure->departure]) . '.pdf';

    if ($data['departure']->departure_reason->note) {
      $options = [
        'orientation' => 'portrait',
        'page-width' => '216',
        'page-height' => '279',
        'margin-top' => '8',
        'margin-bottom' => '16',
        'margin-left' => '20',
        'margin-right' => '20',
        'encoding' => 'UTF-8',
        'user-style-sheet' => public_path('css/report-print.min.css')
      ];

      $pdf = \PDF::loadView('departure.note', $data);
      $pdf->setOptions($options);
    } else {
      $options = [
        'orientation' => 'portrait',
        'page-width' => '216',
        'page-height' => '279',
        'margin-top' => '2',
        'margin-bottom' => '4',
        'margin-left' => '5',
        'margin-right' => '10',
        'encoding' => 'UTF-8',
        'user-style-sheet' => public_path('css/report-print.min.css')
      ];

      $pdf = \PDF::loadView('departure.print', $data);
      $pdf->setOptions($options);
    }

    return $pdf->stream($file_name);
  }

  function report_print(Request $request, $type)
  {
    $request['type'] = $type;
    $request['approved'] = 'all';
    $data = array('departures' => $this->index($request));

    $date = (object)[
      'from' => $request['from'],
      'to' => $request['to']
    ];

    $data['title'] = (object)[
      'name' => 'SOLICITUDES DE SALIDAS Y LICENCIAS',
      'date' => $date,
      'type' => ($type == 'consultant') ? 'CONSULTORES' : 'EVENTUALES'
    ];

    $file_name = implode('_', ['solicitudes', 'salidas', $date->from, $date->to]) . '.pdf';

    $footerHtml = view()->make('partials.footer')->with(array('paginator' => true, 'print_date' => true, 'date' => Carbon::now()->ISOFormat('L H:m')))->render();

    $options = [
      'orientation' => 'landscape',
      'page-width' => '216',
      'page-height' => '279',
      'margin-top' => '8',
      'margin-bottom' => '16',
      'margin-left' => '5',
      'margin-right' => '5',
      'encoding' => 'UTF-8',
      'footer-html' => $footerHtml,
      'user-style-sheet' => public_path('css/report-print.min.css')
    ];

    $pdf = \PDF::loadView('departure.report', $data);
    $pdf->setOptions($options);

    return $pdf->stream($file_name);
  }

  public function transfer(Request $request, $id)
  {
    $departure = Departure::findOrFail($id);
    $departure->destiny = $request->destiny;

    $data = [
      'departure' => $departure,
      'transfers' => $request->transfers
    ];

    $file_name = implode('_', ['solicitud', 'pasajes', $data['departure']->employee->first_name, $data['departure']->employee->last_name, $departure->departure]) . '.pdf';

    $options = [
      'orientation' => 'portrait',
      'page-width' => '216',
      'page-height' => '279',
      'margin-top' => '2',
      'margin-bottom' => '4',
      'margin-left' => '5',
      'margin-right' => '10',
      'encoding' => 'UTF-8',
      'user-style-sheet' => public_path('css/report-print.min.css')
    ];

    $pdf = \PDF::loadView('departure.transfers', $data);
    $pdf->setOptions($options);

    return $pdf->stream($file_name);
  }

  public function verify_cite(Request $request)
  {
    $request->validate([
      'cite' => 'required|string|min:2'
    ]);
    $departure = Departure::where(function($query) {
      $query->orWhere('approved', true)->orWhere('approved', null);
    })->whereCite($request['cite'])->first();
    if (!$departure) {
      return response()->json([
        'message' => 'Valid cite',
      ], 200);
    } else {
      return response()->json([
        'message' => 'bad database formed',
        'errors' => [
          'type' => ['El CITE ya existe'],
        ]
      ], 409);
    }
  }

  public function vacation_departure(DepartureForm $request)
  {
    DB::beginTransaction();
    try{
      $vacation_queue = VacationQueue::where('employee_id', $request->employee_id)->where('max_date', '>=', $request->departure)->where('rest_days','>','0')->get();
      if($vacation_queue->sum('rest_days') > 0 && count($request->days) <= $vacation_queue->sum('rest_days'))
      {
        $lastCode = Departure::latest()->first()->code;
        $newCode = $lastCode + 1;
        $departure = Departure::create(array_merge($request->all(), [
          'code' => $newCode,
        ]));
        $c = 1;
        foreach($request->days as $day)
        {
          $journal = 1;
          if($c==1 || $c==count($request->days))
          {
            if($day['morning'] && !$day['afternoon'] || !$day['morning'] && $day['afternoon'])
              $journal = 0.5;
          }
          $remanent = VacationQueue::where('employee_id', $request->employee_id)->where('max_date', '>=',Carbon::parse($day['date'])->format('Y-m-d'))->where('rest_days', '>',  0)->orderby('max_date', 'asc')->first();
          $remanent->rest_days = $remanent->rest_days - $journal;
          $remanent->save();
          $save_day = new DaysOnVacation([
            'date' => $day['date'],
            'day' => $journal,
          ]);
          $departure->days_on_vacation()->save($save_day);
          $c++;
        }
        DB::commit();
        return response()->json([
          'message' => 'Registro exitoso',
        ], 200);
      }
      else{
        return response()->json([
          'message' => 'Dias de Vacación no disponibles',
        ], 200);
      }
    }catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'message' => 'Cite Duplicado',
      ], 500);
    }
  }

  public function cancel_vacation_departure($id)
  {
    DB::beginTransaction();
    try{
      $departure = Departure::find($id);
      if($departure && $departure->departure_reason->name == 'VACACIONES')
      {
        $departure->days_on_vacation;
        foreach($departure->days_on_vacation as $day)
        {
          $vacation_queue = VacationQueue::where('employee_id', $departure->employee_id)->where('max_date', '>=', $day->date)->orderBy('max_date', 'asc')->first();
          $vacation_queue->rest_days = $vacation_queue->rest_days + $day->day;
          $vacation_queue->update();
        }
        if ($departure->approved === null)
        {
          $departure->days_on_vacation()->delete();
          $departure->delete();
        }
        DB::commit();
        return response()->json([
          'message' => 'Permiso de Vacacion eliminado',
        ], 200);
      }else{
        DB::commit();
        return response()->json([
          'message' => 'Registro inexistente',
        ], 200);
      }
    }catch(\Exception $e){
      DB::rollback();
      return response()->json([
        'message' => "Algo sucedio",
      ], 500);
    }
  }
}
