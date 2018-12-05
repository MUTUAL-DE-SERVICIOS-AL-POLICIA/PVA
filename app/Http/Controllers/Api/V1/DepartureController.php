<?php

namespace App\Http\Controllers\Api\V1;

use App\Certificate;
use App\Contract;
use App\Departure;
use App\PositionGroup;
use App\Employee;
use App\DepartureReason;
use App\EmployeeDeparture;
use App\Http\Controllers\Controller;
use Carbon;
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
    public function index()
    {
        return Departure::with('contract', 'contract.employee', 'contract.employee.city_identity_card', 'contract.position', 'departure_reason.departure_type', 'departure_reason', 'certificate')
            ->orderBy('created_at', 'DESC')
            ->get();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->departure_time == null) {
            $request->departure_time = '08:00';
        }
        if ($request->return_time == null) {
            $request->return_time = '18:30';
        }
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
        return Departure::findOrFail($id);
    }

    /**
     * Display the specified contract resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_departures($contract_id)
    {
        return Departure::with('contract', 'contract.employee', 'contract.employee.city_identity_card', 'contract.position', 'departure_reason.departure_type', 'departure_reason', 'certificate')
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
        $month               = Carbon::now()->month;
        $year                = Carbon::now()->year;
        $hours               = 0;
        $days                = 0;
        $total_minutes_month = 0;
        $total_minutes_year  = 0;
        $dep                 = [];
        $contracts           = Contract::where('employee_id', $employee_id)->get();
        $departure_hour = DepartureReason::where('departure_type_id', 1)->where('name', 'Personal')->first();
        $departure_day = DepartureReason::where('departure_type_id', 2)->where('name', 'Personal')->first();
        foreach ($contracts as $contract) {
            $departures = Departure::where('contract_id', $contract->id)->get();
            foreach ($departures as $departure) {
                $e               = new EmployeeDeparture($departure);
                $departure_days = Carbon::parse($departure->departure_date)->month;
                
                $departure_year  = Carbon::parse($departure->departure_date)->year;
                if ($departure_days <= 19) {
                    $departure_month1 = Carbon::parse($departure->departure_date)->subMonth()->month;
                    $departure_month2 = Carbon::parse($departure->departure_date)->month;
                } else {
                    $departure_month1 = Carbon::parse($departure->departure_date)->month;
                    $departure_month2 = Carbon::parse($departure->departure_date)->addMonth()->month;
                }
                if ($departure->departure_date >= $departure_year.'-'.$departure_month1.'-20' && $departure->departure_date <= $departure_year.'-'.$departure_month2.'-19') {
                    if ($departure->departure_reason->departure_type_id == 1 && $departure->departure_reason->name == 'Personal' && $departure->approved == true) {
                        $total_minutes_month = $total_minutes_month + $e->departure_minutes;
                    }
                }
                if ($year == $departure_year) {
                    if ($departure->departure_reason->departure_type_id == 2 && $departure->departure_reason->name == 'Personal' && $departure->approved == true) {
                        // if ($departure->on_vacation != false) {
                            $total_minutes_year = $total_minutes_year + $e->departure_minutes;
                        // }
                    }
                }
                $dep[] = $e;
            }
        }
        $total_departure['total_minutes_month']      = $total_minutes_month;
        $total_departure['total_minutes_month_rest'] = $departure_hour->hour * 60 - $total_minutes_month;
        $total_departure['total_minutes_year']       = $total_minutes_year;
        $total_departure['total_minutes_year_rest']  = $departure_day->day * 8 * 60 - $total_minutes_year;
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
        $departure->delete();
        $certificate = Certificate::findOrFail($departure->certificate_id);
        $certificate->delete();
        return $departure;
    }

    /**
     * PDF the specified resource from storage.
     *
     * @param  int  $id
     * @return pdf
     */
    function print($departure_id) 
    {        
        $pageWidth   = '216';
        $pageHeight  = '279';
        $pageMargins = [5, 5, 5, 10];
        $pageName    = 'Solicitud.pdf';
        
        $data        = [
            'departure' => Departure::findOrFail($departure_id)
        ];
        return \PDF::loadView('departure.print', $data)
            ->setOption('page-width', $pageWidth)
            ->setOption('page-height', $pageHeight)
            ->setOption('margin-top', $pageMargins[0])
            ->setOption('margin-right', $pageMargins[1])
            ->setOption('margin-bottom', $pageMargins[2])
            ->setOption('margin-left', $pageMargins[3])
            ->setOption('encoding', 'utf-8')
            ->stream($pageName);
    }
    function print_report(Request $request) 
    {
        $search['state'] = $request->state;
        $search['type'] = $request->type;
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
        if ($request->type != null) {            
            $query->where('departure_reasons.departure_type_id', $request->type);   
        }
        $res = $query->get();
        $pageWidth   = '216';
        $pageHeight  = '279';
        $pageMargins = [10, 10, 10, 15];
        $pageName    = 'Reporte de salidas/licencias.pdf';
        $data        = [
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
            ->stream($pageName);
    }
}
