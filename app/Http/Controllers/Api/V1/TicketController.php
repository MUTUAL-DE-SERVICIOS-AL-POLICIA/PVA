<?php
namespace App\Http\Controllers\Api\V1;

use App\EmployeePayroll;
use App\Http\Controllers\Controller;
use App\Payroll;
use App\Procedure;
use App\Company;
use App\CompanyAddress;

class TicketController extends Controller
{
  function print($id)
  {
    $procedure = Procedure::findOrFail($id);

    $company = Company::first();
    $company->address = CompanyAddress::where('active', true)->first()->address;

    $grouped_payrolls = Payroll::where('procedure_id', $procedure->id)->leftJoin('employees as e', 'e.id', '=', 'payrolls.employee_id')->leftJoin('contracts as c', 'c.id', '=', 'payrolls.contract_id')->orderBy('e.last_name')->orderBy('e.mothers_last_name')->orderBy('c.start_date')->get()->groupBy('code');
    $payrolls = [];
    foreach ($grouped_payrolls as $payroll_group) {
      foreach ($payroll_group as $key => $pr) {
        if ($key == 0) {
          $p = new EmployeePayroll($pr);
        } else {
          $p->mergePayroll(new EmployeePayroll($pr));
        }
      }
      $p = $p->generateImage();
      $payrolls[] = $p;
    }

    $file_name = "boletas_de_pago_" . $procedure->month->name . "_" . $procedure->year . ".pdf";

    $data = [
      'payrolls' => $payrolls,
      'company' => $company,
      'procedure' => $procedure
    ];

    $options = [
      'orientation' => 'portrait',
      'page-width' => '216',
      'page-height' => '427',
      'margin-left' => '0',
      'margin-right' => '0',
      'margin-top' => '0',
      'margin-bottom' => '0',
      'encoding' => 'UTF-8',
      'user-style-sheet' => public_path('css/ticket-standalone.min.css')
    ];

    $pdf = \PDF::loadView('tickets.standalone', $data);
    $pdf->setOptions($options);

    return $pdf->stream($file_name);
  }

  function standalone($code)
  {
    if (!$request->has('procedure_id')) {
      abort(404);
    }
    $grouped_payrolls = Payroll::where('code', $code)->whereProcedureId($request->input('procedure_id'))->leftJoin('employees as e', 'e.id', '=', 'payrolls.employee_id')->leftJoin('contracts as c', 'c.id', '=', 'payrolls.contract_id')->orderBy('c.start_date')->get();

    $company = Company::first();
    $company->address = CompanyAddress::where('active', true)->first()->address;

    $payrolls = [];
    foreach ($grouped_payrolls as $key => $payroll) {
      reset($grouped_payrolls);
      if ($key == key($grouped_payrolls)) {
        $p = new EmployeePayroll($payroll);
        $procedure = $payroll->procedure;
      } else {
        $p->mergePayroll(new EmployeePayroll($payroll));
      }

      end($grouped_payrolls);
      if ($key == key($grouped_payrolls)) {
        $p = $p->generateImage();
        $payrolls[] = $p;
      }
    }

    $data = [
      'payrolls' => $payrolls,
      'company' => $company,
      'procedure' => $procedure
    ];

    $file_name = "boleta_" . $code . ".pdf";

    $options = [
      'orientation' => 'portrait',
      'page-width' => '216',
      'page-height' => '356',
      'margin-left' => '0',
      'margin-right' => '0',
      'margin-top' => '0',
      'margin-bottom' => '0',
      'encoding' => 'UTF-8',
      'user-style-sheet' => public_path('css/ticket-standalone.min.css')
    ];

    $pdf = \PDF::loadView('tickets.standalone', $data);
    $pdf->setOptions($options);

    return $pdf->stream($file_name);
  }
}
