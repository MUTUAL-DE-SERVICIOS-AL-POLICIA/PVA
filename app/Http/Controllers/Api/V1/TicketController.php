<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Util;
use App\Payroll;
use App\Procedure;
use Carbon\Carbon;
use \Milon\Barcode\DNS2D;

class TicketController extends Controller
{
    private function mergeTickets($payrolls)
    {
        $payroll;

        foreach ($payrolls as $key => $item) {
            if ($key == 0) {
                $payroll = $this->generateTicket($item);
            }
            if ($key > 0) {
                $payroll->worked_days += $item->worked_days;
                $payroll->quotable += $item->quotable;
                $payroll->discount_old += $item->discount_old;
                $payroll->discount_common_risk += $item->discount_common_risk;
                $payroll->discount_commission += $item->discount_commission;
                $payroll->discount_solidary += $item->discount_solidary;
                $payroll->discount_rc_iva += $item->discount_rc_iva;
                $payroll->discount_faults += $item->discount_faults;
                $payroll->total_discounts += $item->total_discounts;
                $payroll->payable_liquid += $item->payable_liquid;
            }
        }
        return $payroll;
    }

    private function generateTicket($payroll)
    {
        $contract                    = $payroll->contract;
        $position                    = $contract->position;
        $charge                      = $position->charge;
        $employee                    = $contract->employee;
        $payroll->contract_id        = $contract->id;
        $payroll->ci_ext             = Util::ciExt($employee);
        $payroll->employee_id        = $employee->id;
        $payroll->city_identity_card = $employee->city_identity_card->shortened;
        $payroll->full_name          = Util::fullName($employee);
        $payroll->birth_date         = Carbon::parse($employee->birth_date)->format('d/m/Y');
        $payroll->account_number     = $employee->account_number;
        $payroll->nua_cua            = $employee->nua_cua;
        $payroll->charge             = $charge->name;
        $payroll->position           = $position->name;
        $payroll->base_wage          = $charge->base_wage;
        $payroll->management_entity  = $employee->management_entity->name;
        $payroll->code_image         = \DNS2D::getBarcodePNG(($payroll->id . ' ' . $contract->id . ' ' . $position->id . ' ' . $charge->id . ' ' . $employee->id), "PDF417", 3, 33, array(1, 1, 1));

        return $payroll;
    }

    function print($year, $month) {
        $procedure = Procedure::select('procedures.id', 'procedures.month_id', 'procedures.year')
            ->leftJoin("months", 'months.id', '=', 'procedures.month_id')
            ->whereRaw("lower(months.name) like '" . strtolower($month) . "'")
            ->where('year', '=', $year)
            ->first();
        if (!$procedure) {
            return "procedure not found";
        }

        $grouped_payrolls = Payroll::where('procedure_id', $procedure->id)->get()->groupBy('code')->all();
        // if (config('app.debug')) {
        //     $grouped_payrolls = Payroll::where('procedure_id', $procedure->id)->skip(5)->take(10)->get()->groupBy('code')->all();
        // }

        // $payrolls = array();

        // foreach ($grouped_payrolls as $key => $payroll_group) {
        //     $payrolls[] = $this->mergeTickets($payroll_group);
        // }
        // $file_name = "Boletas de Pago de " . $procedure->month->name . " de " . $procedure->year . ".pdf";
        // $data      = [
        //     'payrolls'  => $payrolls,
        //     'procedure' => $procedure,
        // ];
        // // return view('print.temp');
        // // return view('tickets.print',$data);
        // return \PDF::loadView('tickets.print', $data)
        //     ->setOption('page-width', '216')
        //     ->setOption('page-height', '356')
        // // ->setPaper('letter')
        //     ->setOption('margin-top', 0)
        //     ->setOption('margin-bottom', 0)
        //     ->setOption('margin-left', 4)
        //     ->setOption('margin-right', 4)
        //     ->setOption('encoding', 'utf-8')
        //     ->stream($file_name);
        return $procedure;
    }
}