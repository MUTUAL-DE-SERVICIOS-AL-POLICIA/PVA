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
    $date = $this->current_date();

    $query = Departure::join('departure_reasons', 'departures.departure_reason_id', '=', 'departure_reasons.id')->select('departures.*', 'departure_reasons.description_needed', 'departure_reasons.note')->orderBy('approved', 'DESC')->orderBy('departures.created_at');

    if (!$request->has('date_range')) {
      $request['date_range'] = 'monthly';
    }

    if ($request['date_range'] == 'monthly') {
      if ($request->has('from_date') && $request->has('to_date')) {
        $date->from = Carbon::parse($request['from_date'])->startOfDay();
        $date->to = Carbon::parse($request['to_date'])->endOfDay();
      }

      $query = $query->where(function ($q) use ($date) {
        return $q->whereDate('departure', '>=', $date->from)->whereDate('departure', '<=', $date->to);
      })->orWhere(function ($q) use ($date) {
        return $q->whereDate('return', '>=', $date->from)->whereDate('return', '<=', $date->to);
      })->orWhere(function ($q) use ($date) {
        return $q->whereDate('departure', '<=', $date->from)->whereDate('return', '>=', $date->to);
      });
    }

    if ($request->has('employee_id')) {
      return $query->where('employee_id', intval($request['employee_id']))->get();
    } else {
      return $query->with('employee')->get();
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(DepartureForm $request)
  {
    $departure = Departure::create($request->all());
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
   * Display the specified contract resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function get_departures($contract_id)
  {
    return Departure::with('contract', 'contract.employee', 'contract.employee.city_identity_card', 'contract.position', 'departure_reason')
      ->where('contract_id', $contract_id)
      ->orderBy('created_at', 'DESC')
      ->get();
  }

  /**
   * Display the specified contract resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function get_departures_used($employee_id)
  {
    $month = Carbon::now()->month;
    $year = Carbon::now()->year;
    $hours = 0;
    $days = 0;
    $total_minutes_month = 0;
    $total_minutes_year = 0;
    $dep = [];
    $contracts = Contract::where('employee_id', $employee_id)->get();
    $departure_hour = 2;
    $departure_day = 2;
    foreach ($contracts as $contract) {
      $departures = Departure::where('contract_id', $contract->id)->get();
      foreach ($departures as $departure) {
        $e = new EmployeeDeparture($departure);
        $departure_days = Carbon::parse($departure->departure_date)->day;
        $departure_year = Carbon::parse($departure->departure_date)->year;
        if ($departure_days <= 19) {
          $departure_month1 = Carbon::parse($departure->departure_date)->subMonth()->month;
          $departure_month2 = Carbon::parse($departure->departure_date)->month;
        } else {
          $departure_month1 = Carbon::parse($departure->departure_date)->month;
          $departure_month2 = Carbon::parse($departure->departure_date)->addMonth()->month;
        }

        $start_date = Carbon::parse($departure_year . '-0' . $departure_month1 . '-20')->format('Y-m-d');
        $end_date = Carbon::parse($departure_year . '-' . $departure_month2 . '-19')->format('Y-m-d');
        if ($departure->departure_date >= $start_date && $departure->departure_date <= $end_date) {
          if ($departure->departure_reason->reset == 'monthly' && $departure->approved == true) {
            $total_minutes_month = $total_minutes_month + $e->departure_minutes;
          }
        }
        if ($year == $departure_year) {
          if ($departure->departure_reason->reset == 'annually' && $departure->approved == true) {
            // if ($departure->on_vacation != false) {
            $total_minutes_year = $total_minutes_year + $e->departure_minutes;
            // }
          }
        }
        $dep[] = $e;
      }
    }
    $total_departure['total_minutes_month'] = $total_minutes_month;
    $total_departure['total_minutes_month_rest'] = $departure_hour * 60 - $total_minutes_month;
    $total_departure['total_minutes_year'] = $total_minutes_year;
    $total_departure['total_minutes_year_rest'] = $departure_day * 8 * 60 - $total_minutes_year;
    return $total_departure;
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

  function print_report(Request $request)
  {
    $search['state'] = $request->state;
    $search['start_date'] = $request->start_date;
    $search['end_date'] = $request->end_date;

    $query = Departure::join('contracts', 'contracts.id', '=', 'departures.contract_id')
      ->join('positions', 'positions.id', '=', 'contracts.position_id')
      ->join('departure_reasons', 'departure_reasons.id', '=', 'departures.departure_reason_id')
      ->whereBetween('departures.departure_date', [$request->start_date, $request->end_date]);
    if ($request->position_group_id != null) {
      $query->where('positions.position_group_id', $request->position_group_id);
      $search['position_group'] = PositionGroup::findOrFail($request->position_group_id);
    }
    if ($request->employee_id != null) {
      $query->where('contracts.employee_id', $request->employee_id);
      $search['employee'] = Employee::findOrFail($request->employee_id);
    }
    if ($request->state != null) {
      $query->where('departures.approved', $request->state);
    }
    $res = $query->get();
    $pageWidth = '216';
    $pageHeight = '279';
    $pageMargins = [10, 10, 10, 15];
    $pageName = 'Reporte de salidas/licencias.pdf';
    $data = [
      'departures' => $res,
      'search' => (object)$search
    ];
    return \PDF::loadView('departure.report', $data)
      ->setOption('page-width', $pageWidth)
      ->setOption('page-height', $pageHeight)
      ->setOption('margin-top', $pageMargins[0])
      ->setOption('margin-right', $pageMargins[1])
      ->setOption('margin-bottom', $pageMargins[2])
      ->setOption('margin-left', $pageMargins[3])
      ->setOption('footer-font-size', 5)
      ->setOption('footer-center', '[page] de [topage] - Impreso el ' . date('m/d/Y H:i'))
      ->setOption('encoding', 'utf-8')
      ->setOption('user-style-sheet', public_path('css/report-print.min.css'))
      ->stream($pageName);
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
