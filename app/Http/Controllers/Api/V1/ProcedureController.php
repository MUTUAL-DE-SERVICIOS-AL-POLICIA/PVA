<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\EmployeeDiscount;
use App\EmployeePayroll;
use App\EmployerContribution;
use App\MinimumSalary;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcedureForm;
use App\Procedure;
use App\Payroll;
use App\Company;
use App\CompanyAccount;
use Illuminate\Http\Request;
use Response;
use Util;

/** @resource Procedure
 *
 * Resource to retrieve, store and update procedures data
 */

class ProcedureController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return Procedure::with('month')->get();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ProcedureForm $request)
  {
    if (Procedure::where('active', true)->whereNull('deleted_at')->count() == 0) {
      if (Procedure::where('year', $request['year'])->where('month_id', $request['month_id'])->whereNull('deleted_at')->count() == 0) {
        $discount = EmployeeDiscount::where('active', true)->first();
        $contribution = EmployerContribution::where('active', true)->first();
        $minimum_salary = MinimumSalary::latest()->first();
        $procedure = new Procedure();
        $procedure->year = $request['year'];
        $procedure->month_id = $request['month_id'];
        $procedure->employee_discount_id = $discount->id;
        $procedure->employer_contribution_id = $contribution->id;
        $procedure->minimum_salary_id = $minimum_salary->id;
        $procedure->worked_days = $request['worked_days'];
        $procedure->active = true;
        $procedure->save();
        return $procedure;
      } else {
        return response()->json([
          'message' => 'No autorizado',
          'errors' => [
            'type' => ['Ya existe el registro de ese mes'],
          ],
        ], 400);
      }
    } else {
      return response()->json([
        'message' => 'No autorizado',
        'errors' => [
          'type' => ['Debe cerrar las planillas activas'],
        ],
      ], 403);
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
    return Procedure::with('month')->findOrFail($id);
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
    $procedure = Procedure::findOrFail($id);
    $procedure->fill($request->all());
    $procedure->save();
    $procedure->month;
    return $procedure;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $procedure = Procedure::findOrFail($id);
    $procedure->delete();
    return $procedure;
  }

  /**
   * Display the specified procedure's discounts.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function discounts($id)
  {
    return Procedure::where('id', $id)->with('month')->with('employee_discount')->with('employer_contribution')->first();
  }

  /**
   * Display the date of procedure.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function date($id)
  {
    $procedure = Procedure::findOrFail($id);
    return response()->json([
      'first_date' => Carbon::create($procedure->year, $procedure->month->order)->startOfMonth()->format('Y-m-d'),
      'end_date' => Carbon::create($procedure->year, $procedure->month->order)->endOfMonth()->format('Y-m-d'),
      'now' => Carbon::now()->format('Y-m-d'),
    ]);
  }

  /**
   * Display a last procedures stored in DB.
   *
   * @return \Illuminate\Http\Response
   */
  public function order($order)
  {
    return Procedure::leftjoin('months as m', 'm.id', '=', 'month_id')->orderBy('year', ($order == 'last') ? 'DESC' : 'ASC')->orderBy('m.order', ($order == 'last') ? 'DESC' : 'ASC')->select('procedures.*')->first();
  }

  /**
   * Update a pay_date procedures stored in DB.
   *
   * @return \Illuminate\Http\Response
   */
  public function pay_date($id, $date)
  {
    $ufv = $this->get_ufv($date);
    if ($ufv) {
      $procedure = Procedure::find($id);
      $procedure->pay_date = $date;
      $procedure->ufv = floatval(str_replace(',', '.', $ufv));
      $procedure->save();
      return $procedure;
    } else {
      return response()->json([
        'message' => 'Data not found',
        'errors' => [
          'type' => ['Dato de UFV inexistente en BCB'],
        ]
      ], 400);
    }
  }

  private function get_ufv($date)
  {
    $day = Carbon::parse($date)->day;
    $month = Carbon::parse($date)->month;
    $year = Carbon::parse($date)->year;
    $extracto = null;

    $content = file_get_contents('https://www.bcb.gob.bo/librerias/indicadores/ufv/gestion.php?sdd=' . $day . '&smm=' . $month . '&saa=' . $year . '&Button=++Ver++&reporte_pdf=' . $month . '*' . $day . '*' . $year . '**' . $month . '*' . $day . '*' . $year . '*&edd=' . $day . '&emm=' . $month . '&eaa=' . $year . '&qlist=1');
    $patron = '|<div align="center">(.*?)</div>|is';
    if (preg_match_all($patron, $content, $extracto) > 0) {
      return trim($extracto[1][1]);
    } else {
      return false;
    }
  }

  public function living_expenses(Request $request, $id)
  {
    $procedure = Procedure::findOrFail($id);
    $procedure->employer_contribution;
    $company = Company::first();
    $grouped_payrolls = Payroll::where('procedure_id', $procedure->id)->leftJoin('employees as e', 'e.id', '=', 'payrolls.employee_id')->leftJoin('contracts as c', 'c.id', '=', 'payrolls.contract_id')->orderBy('e.last_name')->orderBy('e.mothers_last_name')->orderBy('c.start_date')->get()->groupBy('employee_id');
    $payrolls = [];

    foreach ($grouped_payrolls as $payroll_group) {
      foreach ($payroll_group as $key => $pr) {
        if ($key == 0) {
          $p = new EmployeePayroll($pr);
        } else {
          $p->mergePayroll(new EmployeePayroll($pr));
        }
      }
      $payrolls[] = $p;
    }

    $total_payment = 0;
    foreach ($payrolls as $payroll) {
        $payroll->worked_days = $procedure->worked_days - $payroll->unworked_days;
        $payroll->payment = $procedure->employer_contribution->living_expenses * $payroll->worked_days;
        $total_payment += $payroll->payment;
    }

    $file_name = "refrigerios_" . $procedure->month->name . "_" . $procedure->year . ".pdf";

    $data = [
      'employees' => $payrolls,
      'company' => $company,
      'procedure' => $procedure,
      'title' => (object)[
        'name' => 'PLANILLA DE REFRIGERIOS',
        'subtitle' => 'EVENTUAL'
      ],
      'total_payment' => $total_payment
    ];

    if (!$request->has('report_type')) {
      return response()->json($data);
    } else {
      if ($request->report_type == 'pdf') {
        $footerHtml = view()->make('partials.footer')->with(array('paginator' => true, 'print_message' => $data['procedure']->active ? 'Borrador' : null, 'print_date' => true, 'date' => null))->render();

        $options = [
        'orientation' => 'portrait',
        'page-width' => '216',
        'page-height' => '279',
        'margin-top' => '5',
        'margin-right' => '10',
        'margin-left' => '10',
        'margin-bottom' => '15',
        'encoding' => 'UTF-8',
        'footer-html' => $footerHtml,
        'user-style-sheet' => public_path('css/report-print.min.css')
        ];

        $pdf = \PDF::loadView('payroll.living_expenses', $data);
        $pdf->setOptions($options);

        return $pdf->stream($file_name);
      } elseif ($request->report_type == 'txt') {
        $total_employees = count($data['employees']);
        if ($total_employees == 0) {
          abort(404);
        }
        $content = "";
        $content .= "refrigerios del mes de " . strtolower($procedure->month->name) . " " . $procedure->year . " " . Util::fillZerosLeft(strval($total_employees), 4) . Carbon::now()->format('dmY') . "\r\n";
        $content .= CompanyAccount::where('active', true)->first()->account . Util::fillZerosLeft(strval(Util::format_number($data['total_payment'], 2, '', '.')), 12) . "\r\n";
        foreach ($data['employees'] as $i => $employee) {
          if ($employee->account_number) {
            $content .= $employee->account_number . Util::fillZerosLeft(strval(Util::format_number($employee->payment, 2, '', '.')), 12) . "1";
            if ($i < ($total_employees - 1)) {
              $content .= "\r\n";
            }
          }
        }
        $filename = implode('_', ["refrigerios", strtolower($procedure->month->name), $procedure->year]) . ".txt";
        $headers = [
          'Content-type' => 'text/plain',
          'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)
        ];
        return Response::make($content, 200, $headers);
      }
    }
  }
}
