<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Payroll;
use App\Procedure;
use \Milon\Barcode\DNS2D;
use App\EmployeePayroll;
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
        $contract = $payroll->contract;
        $position = $contract->position;
        $charge   = $position->charge;
        $employee = $contract->employee;

        $pays = Payroll::where('code', $payroll->code)->get();
        $worked_days          = 0;
        $quotable             = 0;
        $discount_old         = 0;
        $discount_common_risk = 0;
        $discount_commission  = 0;
        $discount_solidary    = 0;
        $discount_rc_iva      = 0;
        $discount_faults      = 0;
        $total_discounts      = 0;
        $payable_liquid       = 0;

        foreach ($pays as $key => $pay) {
            $p = new EmployeePayroll($pay);
            $payroll = $p;
            $worked_days          = $worked_days + $p->worked_days ;
            $quotable             = $quotable + $p->quotable;
            $discount_old         = $discount_old + $p->discount_old;
            $discount_common_risk = $discount_common_risk + $p->discount_common_risk;
            $discount_commission  = $discount_commission + $p->discount_commission;
            $discount_solidary    = $discount_solidary + $p->discount_solidary;
            $discount_rc_iva      = $discount_rc_iva + $p->discount_rc_iva;
            $discount_faults      = $discount_faults + $p->discount_faults;
            $total_discounts      = $total_discounts + $p->total_discounts;
            $payable_liquid       = $payable_liquid + $p->payable_liquid;
        }
        $payroll->worked_days          = $worked_days;
        $payroll->quotable             = $quotable;
        $payroll->discount_old         = $discount_old;
        $payroll->discount_common_risk = $discount_common_risk;
        $payroll->discount_commission  = $discount_commission;
        $payroll->discount_solidary    = $discount_solidary;
        $payroll->discount_rc_iva      = $discount_rc_iva;
        $payroll->discount_faults      = $discount_faults;
        $payroll->total_discounts      = $total_discounts;
        $payroll->payable_liquid       = $payable_liquid;
        $payroll->code_image = DNS2D::getBarcodePNG(($payroll->id . ' ' . $contract->id . ' ' . $position->id . ' ' . $charge->id . ' ' . $employee->id), "PDF417", 3, 33, array(1, 1, 1));
        return $payroll;
    }

    function print($id) {
        $procedure = Procedure::select('procedures.id', 'procedures.month_id', 'procedures.year')
            ->leftJoin("months", 'months.id', '=', 'procedures.month_id')
            ->where('procedures.id', '=', $id)
            ->first();
        if (!$procedure) {
            return "procedure not found";
        }
        $grouped_payrolls = Payroll::where('procedure_id', $procedure->id)->get()->groupBy('code')->all();
        $payrolls         = [];
        foreach ($grouped_payrolls as $payroll_group) {
            $payrolls[] = $this->mergeTickets($payroll_group);
        }
        $file_name = "Boletas de Pago de " . $procedure->month->name . " de " . $procedure->year . ".pdf";
        $data      = [
            'payrolls'  => $payrolls,
            'procedure' => $procedure,
        ];
        return \PDF::loadView('tickets.print', $data)
            ->setOption('page-width', '216')
            ->setOption('page-height', '356')
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 4)
            ->setOption('margin-right', 4)
            ->setOption('encoding', 'utf-8')
            ->stream($file_name);
    }
}
