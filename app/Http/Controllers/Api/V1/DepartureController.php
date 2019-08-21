<?php

namespace App\Http\Controllers\Api\V1;

use App\Contract;
use App\Departure;
use App\PositionGroup;
use App\Employee;
use App\DepartureReason;
use App\EmployeeDeparture;
use App\Http\Requests\DepartureForm;
use App\Http\Controllers\Controller;
use Carbon;
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
    if (!$request->has('from') && !$request->has('to')) {
      $date = $this->current_date();
    } else {
      $date = (object)[
        'from' => null,
        'to' => null
      ];
    }

    $query = Departure::join('departure_reasons', 'departures.departure_reason_id', '=', 'departure_reasons.id')->select('departures.*', 'departure_reasons.description_needed', 'departure_reasons.note');

    if (!$request->has('date_range')) {
      $request['date_range'] = 'monthly';
    }

    if ($request['date_range'] == 'monthly') {
      if ($request->has('from') && $request->has('to')) {
        $date->from = $request['from'];
        $date->to = $request['to'];
      }

      $query = $query->where(function ($subQuery) use ($date) {
        $subQuery->where(function ($q) use ($date) {
          return $q->whereDate('departure', '>=', $date->from)->whereDate('departure', '<=', $date->to);
        })->orWhere(function ($q) use ($date) {
          return $q->whereDate('return', '>=', $date->from)->whereDate('return', '<=', $date->to);
        })->orWhere(function ($q) use ($date) {
          return $q->whereDate('departure', '<=', $date->from)->whereDate('return', '>=', $date->to);
        });
      });
    }

    if ($request->query->has('employee_id')) {
      $query = $query->where('employee_id', intval($request['employee_id']));
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
    $departure = null;
    if ($request->has('cite')) {
      if ($request['cite'] != null) $departure = Departure::whereCite($request['cite'])->first();
    }
    if (!$departure) {
      $departure = Departure::create($request->all());
      return $departure;
    } else {
      return response()->json([
        'message' => 'bad database formed',
        'errors' => [
          'type' => ['El CITE ya existe'],
        ]
      ], 409);
    }
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
    if ($departure->approved === null) $departure->delete();
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
        'margin-top' => '10',
        'margin-bottom' => '10',
        'margin-left' => '25',
        'margin-right' => '25',
        'encoding' => 'UTF-8',
        'user-style-sheet' => public_path('css/report-print.min.css')
      ];

      $pdf = \PDF::loadView('departure.note', $data);
      $pdf->setOptions($options);
    } else {
      $options = [
        'orientation' => 'portrait',
        'page-width' => '216',
        'page-height' => '356',
        'margin-top' => '1',
        'margin-bottom' => '0',
        'margin-left' => '2.5',
        'margin-right' => '2.5',
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
    $data = array('departures' => $this->index($request));

    $date = (object)[];
    if ($request->has('from_date') && $request->has('to_date')) {
      $date->from = $request['from_date'];
      $date->to = $request['to_date'];
    } else {
      $date = $this->current_date();
    }
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

  private function current_date()
  {
    $current = CarbonImmutable::now();
    if ($current->day <= 19) {
      return (object)[
        'from' => $current->subMonths(1)->days(20)->startOfDay(),
        'to' => $current->days(19)->endOfDay()
      ];
    } else {
      return (object)[
        'from' => $current->days(20)->startOfDay(),
        'to' => $current->addMonths(1)->days(19)->endOfDay()
      ];
    }
  }
}
