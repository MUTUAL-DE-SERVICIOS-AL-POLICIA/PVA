<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollForm;
use App\Payroll;
use App\Procedure;
use Illuminate\Http\Request;

/** @resource Payroll
 *
 * Resource to retrieve, store and update Payrolls data
 */
class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Payroll::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PayrollForm $request)
    {
        $procedure      = Procedure::findOrFail($request['procedure_id']);
        $year_shortened = substr(strval($procedure->year), -2);
        if (Payroll::where('code', 'LIKE', "%-$year_shortened")->count() == 0) {
            $code = implode([str_pad(1, 3, '0', STR_PAD_LEFT), $year_shortened], '-');
        } else {
            $payroll = Payroll::where('procedure_id', $request['procedure_id'])->where('charge_id', $request['charge_id'])->where('position_group_id', $request['position_group_id'])->where('position_id', $request['position_id'])->first();
            if ($payroll) {
                $code = $payroll->code;
            } else {
                $last_code = Payroll::where('procedure_id', $procedure->id)->orderBy('code', 'DESC')->select('code')->first();
                if ($last_code) {
                    $last_code = intval(substr($last_code->code, 0, 3));
                } else {
                    $last_code = Payroll::orderBy('code', 'DESC')->select('code')->first();
                    $last_code = intval(substr($last_code->code, 0, 3));
                }
                $code = implode([str_pad($last_code + 1, 3, '0', STR_PAD_LEFT), $year_shortened], '-');
            }
        }
        return Payroll::create($request->all() + ['code' => $code]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Payroll::findOrFail($id);
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
        $payroll = Payroll::findOrFail($id);
        $payroll->fill($request->all());
        $payroll->save();
        return $payroll;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();
        return $payroll;
    }

    /**
     * get payroll especific contract
     *
     * @param  int  $contract_id
     * @return \Illuminate\Http\Response
     */
    public function getPayrollContract($contract_id)
    {
        return Payroll::where('contract_id', $contract_id)->first();
    }

    public function tribute_calculation()
    {
       
        // $fichero  = file_get_contents('https://www.bcb.gob.bo/librerias/indicadores/ufv/ultimo.php');
        // $aux = explode('</strong>', $fichero);
        // $aux2 = explode('&nbsp', $aux[1]);
        // return floatval($aux2[0]);
        $sal_min_nal = 2060;

        $min_disponible = $sal_min_nal * 2;
        $dif_salario_min_disponible = $salary - $min_disponible;
        if ($dif_salario_min_disponible < 0) {
            $dif_salario_min_disponible = 0;
        }
        $idf = $dif_salario_min_disponible * 13 / 100;
        if ($idf > 520) {
            $min_disponible_13 = 520;
        } else {
            $min_disponible_13 = $idf;
        }
        $saldo_favor = $idf - $rciva - $min_disponible_13;
        $fisco = 0;
        $dependiente = 0;
        if (($rciva - $min_disponible_13) < $idf) {
            $fisco = $idf - ($rciva + $min_disponible_13);
        }
        if (($rciva - $min_disponible_13) > $idf) {
            $dependiente = ($rciva + $min_disponible_13) - $idf;
        }
        $saldo_mes_anterior = $mes_anterior;
        $actualizacion = 0;
        if ($salary >= 8000) {
            $actualizacion = 1.002193919;
        }
        $total = $saldo_mes_anterior + $actualizacion;
        $saldo_favor_dependiente = $dependiente + $total;
        if ($fisco > $saldo_favor_dependiente) {
            $saldo_utilizado = $saldo_favor_dependiente;
        } else {
            $saldo_utilizado = $fisco;
        }
        $impuesto_pagar = $fisco - $saldo_utilizado;

        $saldo_mes_siguiente = $saldo_favor_dependiente - $saldo_utilizado;

        $tribute['min_disponible'] = $min_disponible;
        $tribute['dif_salario_min_disponible'] = $dif_salario_min_disponible;
        $tribute['idf'] = $idf;
        $tribute['iva_110'] = $rciva;
        $tribute['min_disponible_13'] = $min_disponible_13;
        $tribute['fisco'] = $fisco;
        $tribute['dependiente'] = $dependiente;
        $tribute['saldo_mes_anterior'] = $saldo_mes_anterior;
        $tribute['actualizacion'] = $actualizacion;
        $tribute['total'] = $total;
        $tribute['saldo_favor_dependiente'] = $saldo_favor_dependiente;
        $tribute['saldo_utilizado'] = $saldo_utilizado;
        $tribute['impuesto_pagar'] = $impuesto_pagar;
        $tribute['saldo_mes_siguiente'] = $saldo_mes_siguiente;
        return (object) $tribute;
    }
}
