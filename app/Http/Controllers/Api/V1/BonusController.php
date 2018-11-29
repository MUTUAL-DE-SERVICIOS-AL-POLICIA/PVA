<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company;
use App\Contract;
use App\EmployeeBonus;
use App\BonusProcedure;
use App\BonusYear;
use App\CompanyAccount;
use App\Helpers\Util;
use Carbon\Carbon;
use Response;

class BonusController extends Controller
{
  /**
   * Display the list of all items.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return BonusProcedure::get();
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\BonusProcedure  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $bonus = BonusProcedure::findOrFail($id);
    return $bonus;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Contract  $contract
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $year = BonusYear::where('year', $request['year'])->first();
    if (!$year) abort(404);
    $bonus = BonusProcedure::where('year', $request['year'])->count();
    if ($bonus < $year->bonus) return BonusProcedure::create($request->all());
    abort(403);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Contract  $contract
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $bonus = BonusProcedure::findOrFail($id);
    $bonus->fill($request->all());
    $bonus->save();
    return $bonus;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Contract  $contract
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $bonus = BonusProcedure::findOrFail($id);
    $bonus->delete();
    return $bonus;
  }

  /**
   * Print the specified resource.
   *
   * @param  \App\BonusProcedure  $id
   * @return \Illuminate\Http\Response
   */
  public function print(Request $request, $id)
  {
    $procedure = BonusProcedure::findOrFail($id);
    $year = $procedure->year;
    $pay_date = $procedure->pay_date;
    $name = $procedure->name;

    $grouped_contracts = Contract::where(function ($query) use ($year) {
      $query
        ->orWhere(function ($q) use ($year) {
          $q
            ->orWhereYear('retirement_date', $year)
            ->orWhereYear('end_date', $year);
        })
        ->orWhere(function ($q) use ($year) {
          $q
            ->where('retirement_date', null)
            ->where('end_date', null)
            ->whereYear('start_date', '<=', $year);
        });
    })->leftjoin('employees as e', 'e.id', '=', 'contracts.employee_id')->select('contracts.*')->orderBy('e.last_name')->orderBy('e.mothers_last_name')->orderBy('start_date')->get()->groupBy('employee_id');

    if (count($grouped_contracts) == 0) abort(404);

    $employees = [];
    foreach ($grouped_contracts as $e => $contracts) {
      $e = new EmployeeBonus($contracts, $year, $pay_date);
      if ($e->worked_days->months >= 3) {
        $employees[] = $e;
      }
    }

    switch ($request['report_type']) {
      case 'txt':
        $total = 0;
        $with_account = [];
        foreach ($employees as $i => $employee) {
          if ($employee->account_number) {
            $with_account[] = $employee;
            $total += round($employee->bonus, 2);
          }
        }
        $employees = $with_account;
        $content = "";
        $content .= strtolower($name) . " " . $year . " " . Util::fillZerosLeft(strval(count($employees)), 4) . Carbon::now()->format('dmY') . "\r\n";
        $content .= CompanyAccount::where('active', true)->first()->account . Util::fillZerosLeft(strval(Util::format_number($total, 2, '', '.')), 12) . "\r\n";
        foreach ($employees as $i => $employee) {
          $content .= $employee->account_number . Util::fillZerosLeft(strval(Util::format_number($employee->bonus, 2, '', '.')), 12) . "1";
          if ($i < (count($employees) - 1)) {
            $content .= "\r\n";
          }
        }
        $filename = implode('_', [str_replace(' ', '_', strtolower($name)), $year]) . ".txt";
        $headers = [
          'Content-type' => 'text/plain',
          'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)
        ];
        return Response::make($content, 200, $headers);
        break;
      case 'csv':
        $content = "";
        $content .= implode(',', ["Nº", "Tipo de documento de identidad", "Número de documento de identidad", "Extensión del documento de identidad", "AFP a la que aporta", "NUA/CUA", "Apellido Paterno", "Apellido Materno", "Apellido de casada", "Primer nombre", "Otros nombres", "País de nacionalidad", "Fecha de nacimiento", "Sexo", "Jubilado", "Clasificación laboral", "Cargo", "Fecha de ingreso", "Modalidad de contrato", "Fecha de retiro", "Promedio haber básico", "Promedio bono de antigüedad", "Promedio bono de producción", "Promedio subsidio de frontera", "Promedio trabajo extraordinario y nocturno", "Promedio pago dominical y domingo trabajado", "Promedio otros bonos", "Promedio total ganado", "Meses trabajados", "Total ganado después de duodécimas", "Sucursal o ubicación adicional", "\r\n"]);

        foreach ($employees as $i => $e) {
          $content .= implode(',', [++$i, "CI", $e->ci, $e->id_ext, $e->ovt->management_entity_id, $e->nua_cua, $e->last_name, $e->mothers_last_name, "", $e->first_name, $e->second_name, "BOLIVIA", $e->birth_date, $e->gender == 'F' ? "0" : "1", "0", "", mb_strtoupper(str_replace(",", " ", $e->position)), $e->start_date, $e->ovt->contract_type, $e->end_date, $e->average, 0, 0, 0, 0, 0, 0, round($e->average, 2), round(($e->worked_days->months + $e->worked_days->days / 30), 2), round(($e->average / 12), 2), "1"]);
          if ($i < count($employees)) {
            $content .= "\r\n";
          }
        }

        $filename = implode('_', ["ovt", str_replace(' ', '_', strtolower($name)), $year]) . ".csv";
        $headers = [
          'Content-type' => 'text/csv',
          'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)
        ];
        return Response::make($content, 200, $headers);

        break;
      default:
        $response = (object)array(
          "data" => [
            'company' => Company::select()->first(),
            'title' => (object)[
              'name' => 'PLANILLA DE AGUINALDOS',
              'year' => $year,
              'subtitle' => $name
            ],
            'pay_date' => $pay_date,
            'employees' => $employees,
          ]
        );

        $file_name = implode(" ", ['PLANILLA', $response->data['title']->subtitle, $year]) . ".pdf";

        return \PDF::loadView('payroll.bonus', $response->data)
          ->setOption('page-width', '216')
          ->setOption('page-height', '330')
          ->setOption('margin-left', '26')
          ->setOption('margin-right', '0')
          ->setOrientation('landscape')
          ->setOption('encoding', 'utf-8')
          ->setOption('footer-font-size', 5)
          ->setOption('footer-center', 'Impreso el ' . date('m/d/Y H:i'))
          ->setOption('encoding', 'utf-8')
          ->stream($file_name);
    }
  }
}
