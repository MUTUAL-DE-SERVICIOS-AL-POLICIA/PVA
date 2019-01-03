<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\ConsultantProcedure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConsultantProcedureController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return ConsultantProcedure::with('month')->get();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if (ConsultantProcedure::where('active', true)->count() == 0) {
      if (ConsultantProcedure::where('year', $request['year'])->where('month_id', $request['month_id'])->count() == 0) {
        $procedure = new ConsultantProcedure();
        $procedure->year = $request['year'];
        $procedure->month_id = $request['month_id'];
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
   * @param  \App\ConsultantProcedure  $consultantProcedure
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    return ConsultantProcedure::with('month')->findOrFail($id);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\ConsultantProcedure  $consultantProcedure
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $procedure = ConsultantProcedure::findOrFail($id);
    $procedure->fill($request->all());
    $procedure->save();
    $procedure->month;
    return $procedure;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\ConsultantProcedure  $consultantProcedure
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $procedure = ConsultantProcedure::findOrFail($id);
    $procedure->delete();
    return $procedure;
  }

  /**
   * Display the date of procedure.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function date($id)
  {
    $procedure = ConsultantProcedure::findOrFail($id);
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
    return ConsultantProcedure::leftjoin('months as m', 'm.id', '=', 'month_id')->orderBy('year', ($order == 'last') ? 'DESC' : 'ASC')->orderBy('m.order', ($order == 'last') ? 'DESC' : 'ASC')->select('consultant_procedures.*')->first();
  }

  /**
   * Update a pay_date procedures stored in DB.
   *
   * @return \Illuminate\Http\Response
   */
  public function pay_date($id, $date)
  {
    $day = Carbon::parse($date)->day;
    $month = Carbon::parse($date)->month;
    $year = Carbon::parse($date)->year;

    // TODO use utils to get ufv
    $content = file_get_contents('https://www.bcb.gob.bo/librerias/indicadores/ufv/gestion.php?sdd=' . $day . '&smm=' . $month . '&saa=' . $year . '&Button=++Ver++&reporte_pdf=' . $month . '*' . $day . '*' . $year . '**' . $month . '*' . $day . '*' . $year . '*&edd=' . $day . '&emm=' . $month . '&eaa=' . $year . '&qlist=1');
    $patron = '|<div align="center">(.*?)</div>|is';
    preg_match_all($patron, $content, $extracto);
    $ufv = trim($extracto[1][1]);

    $procedure = ConsultantProcedure::find($id);
    $procedure->pay_date = $date;
    $procedure->ufv = floatval(str_replace(',', '.', $ufv));
    $procedure->save();
    return $procedure;
  }
}
