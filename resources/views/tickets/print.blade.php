<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boletas de Pago de {{ $procedure->month->name }} de {{ $procedure->year }}</title>
    <style>
      <?php include public_path('css/ticket-print.min.css') ?>
    </style>
</head>

<body>
    <div class="page">
        <div class="text-center border1 rounded" style="margin-top:50%">
            <h1>PLANILLA DE PAGO</h1>
            <h1>MUSERPOL</h1>
            <h2>{{ $procedure->month->name }} - {{ $procedure->year }}</h2>
        </div>
    </div>
    @foreach ($payrolls as $index=>$payroll)
    <div class="m-b-25 ticket">
        <div class="main-left">
            <table class="m-t-20">
                <tr>
                    <td class="w-50 text-left no-padding no-margins align-top">
                        <table style="border-spacing: 0;border-collapse: collapse;">
                            <tr>
                                <td class="w-150-px" rowspan="2">
                                    <div class="text-center">
                                        {{-- <img src="{{ asset('images/logo.jpg') }}" class="w-100" style="opacity:0"> --}}
                                        {{-- <img src="{{ asset('images/logo.jpg') }}" class="w-100"> --}}
                                    </div>
                                </td>
                                <td class="w-50 align-top leading-none text-uppercase text-lg courier text-center align-middle" style="min-height: 18px;height: 18px;max-height: 18px">
                                    {{-- {{ $payroll->id }} --}}
                                    {{-- {{ Util::fillZerosLeft($payroll->id) }} --}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-sm font-bold" >
                                    {{-- TALÓN DEL BENEFICIARIO --}}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="w-50 align-top leading-none">
                        <table>
                            <tr>
                                <td class="no-border text-xxs w-60-px">
                                    {{-- Entidad: --}}
                                </td>
                                <td class="no-border uppercase courier text-xs" colspan="3">
                                    MUTUAL DE SERVICIOS AL POLÍCIA
                                </td>
                            </tr>
                            <tr>
                                <td class="no-border text-xxs w-60-px">
                                    {{-- Dirección: --}}
                                </td>
                                <td class="no-border courier text-xxs" colspan="3">
                                    Av. 6 de Agosto #2354 Z. Sopocachi
                                </td>
                            </tr>
                            <tr>
                                <td class="no-border text-xxs w-60-px">
                                    {{-- Nº Patronal: --}}
                                </td>
                                <td class="no-border uppercase courier text-xxs w-110-px">
                                    001-720-0025
                                </td>
                                <td class="no-border text-xxs w-30-px">
                                    {{-- NIT: --}}
                                </td>
                                <td class="no-border uppercase courier text-xxs">
                                    234578021
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            {{-- /header --}}
            <hr style="border-top: 0.016em solid #5D6975; margin:0; opacity:0;">
            <table class="table-dticket-1 m-b-5 m-t-5">
                <tr>
                    <td class="text-xxs w-95-px">
                        {{-- Nº de Boleta: --}}
                    </td>
                    <td class="uppercase courier text-xs w-235-px">{{ $payroll->code }}</td>
                    <td class="text-xxs w-95-px">
                        {{-- Modalidad de Pago: --}}
                    </td>
                    <td class="uppercase courier text-xs">ABONO EN CUENTA</td>
                </tr>
                <tr>
                    <td class="text-xxs w-95-px">
                        {{-- Concepto de Pago: --}}
                    </td>
                    <td class="uppercase courier text-xs w-235-px">{{ strlen($procedure->month->shortened) > 3 ? '' : 'PAGO DE HABERES ' }}{{ $procedure->month->shortened}} {{ $procedure->year }}</td>
                    <td class="text-xxs w-95-px">
                        {{-- Nº Días Trab.: --}}
                    </td>
                    <td class="uppercase courier text-xs">{{ $payroll->worked_days }}</td>
                </tr>
            </table>

            {{-- personal-info --}}
            <table>
                <tr>
                    <td class="no-border text-xxs w-95-px">
                        {{-- Carnet de Identidad: --}}
                    </td>
                    <td class="uppercase courier text-xs w-120-px">
                        {{ $payroll->ci_ext }}
                    </td>
                    <td  class="no-border text-xxs w-55-px">
                        {{-- Nombre: --}}
                    </td>
                    <td colspan="6" class="uppercase courier text-xs">
                        {{ $payroll->full_name }}
                    </td>
                </tr>
                <tr>
                    <td class="no-border text-xxs w-95-px">
                        {{-- Nº de Cuenta: --}}
                    </td>
                    <td class="no-border uppercase courier text-xs w-120-px">
                        {{ $payroll->account_number }}
                    </td>
                    <td class="no-border text-xxs w-55-px">
                        {{-- Fecha Nac.: --}}
                    </td>
                    <td class="no-border uppercase courier text-xs w-110-px">
                        {{ $payroll->birth_date }}
                    </td>
                    <td class="no-border text-xxs">
                        {{-- Nº Item: --}}
                    </td>
                    <td class="uppercase courier text-xs">
                        {{-- 1871 --}}
                    </td>
                </tr>
                <tr>
                    <td class="no-border text-xxs w-95-px">
                        {{-- A.F.P.: --}}
                    </td>
                    <td class="no-border uppercase courier text-xs w-130-px">
                        {{ $payroll->management_entity }}
                    </td>
                    <td class="no-border text-xxs w-45-px">
                        {{-- N.U.A.: --}}
                    </td>
                    <td class="uppercase courier text-xs" colspan="6">
                        {{ $payroll->nua_cua }}
                    </td>
                </tr>
            </table>
            <div style="min-height: 21px;max-height: 21px; height: 21px;padding:0 0 0 3px;margin:0; line-height:0">
                <div class="text-xxs inline leading-none" style="min-width:28px; max-width:28px;width:28px; display:inline-block">
                    {{-- Cargo: --}}
                </div>
                <div class="uppercase courier text-xs inline leading-none">{{ $payroll->position }}</div>
            </div>
            {{-- /personal-info --}}

            {{-- amounts --}}
            <table class="table-info table-background">

                <thead>
                    <tr class="text-xs text-center font-bold">
                        <td colspan="2">
                            {{-- INGRESOS --}}
                        </td>
                        <td colspan="3">
                            {{-- DESCUENTOS --}}
                            &nbsp;
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-xs text-center border-bottom">
                        <td class="border-bottom w-100-px">
                            {{-- Detalle --}}
                        </td>
                        <td class="border-bottom w-110-px">
                            {{-- Importe Bs. --}}
                        </td>
                        <td class="border-bottom W-200-px">
                            {{-- Acreedor --}}
                        </td>
                        <td class="border-bottom w-100-px">
                            {{-- Importe Bs. --}}
                            &nbsp;
                        </td>
                    </tr>
                    <tr><td></td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td></tr>
                    <tr class="courier">
                        <td class="text-left">SUELDOS</td>
                        <td class="text-right"> {{ Util::formatMoney($payroll->quotable) }}</td>
                        <td class="text-left px-5">AFP.RV.10%</td>
                        <td class="text-right"> {{ Util::formatMoney($payroll->discount_old) }} </td>
                    </tr>
                    {{-- discounts --}}
                    <tr class="courier">
                        <td></td>
                        <td></td>
                        <td class="text-left px-5">AFP.RC.1,71%</td>
                        <td class="text-right"> {{ Util::formatMoney($payroll->discount_common_risk) }} </td>
                    </tr>
                    <tr class="courier">
                        <td></td>
                        <td></td>
                        <td class="text-left px-5">AFP.CM.0,5%</td>
                        <td class="text-right"> {{ Util::formatMoney($payroll->discount_commission) }} </td>
                    </tr>
                    <tr class="courier">
                        <td></td>
                        <td></td>
                        <td class="text-left px-5">AFP.SOL.ASE.0,5%</td>
                        <td class="text-right"> {{ Util::formatMoney($payroll->discount_solidary) }} </td>
                    </tr>
                    <tr class="courier">
                        <td></td>
                        <td></td>
                        <td class="text-left px-5">RC-IVA</td>
                        <td class="text-right"> {{ Util::formatMoney($payroll->discount_rc_iva) }} </td>
                    </tr>
                    <tr class="courier">
                        <td></td>
                        <td></td>
                        <td class="text-left px-5">OTROS DESCUENTOS</td>
                        <td class="text-right"> {{ Util::formatMoney($payroll->discount_faults) }} </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    {{-- /discounts --}}
                    <tr>
                        <td class="text-xs text-left border-top">
                            {{-- Total Ingresos: --}}
                        </td>
                        <td class="text-right courier border-top">{{ Util::formatMoney($payroll->quotable) }}</td>
                        <td class="text-xs text-left border-top">
                            {{-- Total Descuentos: --}}
                        </td>
                        <td class="text-right courier border-top">{{ Util::formatMoney($payroll->total_discounts) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-sm text-left" style="font-weight:bold">
                            {{-- Liquido Pagable en Bs.: --}}
                        </td>
                        <td class="text-lg text-right courier" colspan="3">
                            {{ Util::formatMoney($payroll->payable_liquid) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="border rounded m-t-5 p-5 text-center" style="text-align:center; height:33px">
                <img src="data:image/png;base64, {{ $payroll->code_image }}" alt="Red dot" style="height: 33px; width: 30%;" />
            </div>
            {{-- /amounts --}}
        </div>
        <div class="main-right" >
            <table class="m-t-20">
                <tr>
                    <td class="w-50" rowspan="2" style="min-height:61px;height:61px;max-height:61px;">
                        <div class="text-center">
                            {{-- <img src="{{ asset('images/logo.jpg') }}" class="w-100" style="opacity:0"> --}}
                            {{-- <img src="{{ asset('images/logo.jpg') }}" class="w-100"> --}}
                        </div>
                    </td>
                    <td class="w-50 align-top leading-none text-uppercase text-lg courier text-center align-middle" style="min-height: 18px;height: 18px;max-height: 18px">
                        {{-- {{ $payroll->id }} --}}
                        {{-- {{ Util::fillZerosLeft($payroll->id) }} --}}
                    </td>
                </tr>
                <tr>
                    <td class="text-center text-sm font-bold">
                        {{-- TALÓN DE EFECTIVIZACIÓN --}}
                        &nbsp;
                    </td>
                </tr>
            </table>
            <div class="w-100">
                <table>
                    <tr>
                        <td class="no-border text-xxs w-60-px">
                            {{-- Entidad: --}}
                        </td>
                        <td class="no-border uppercase courier text-xs" colspan="3">
                            MUTUAL DE SERVICIOS AL POLÍCIA
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border text-xxs w-60-px">
                            {{-- Dirección: --}}
                        </td>
                        <td class="no-border courier text-xs" colspan="3">
                            Av. 6 de Agosto #2354 Z. Sopocachi
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border text-xxs w-60-px">
                            {{-- Nº Patronal: --}}
                        </td>
                        <td class="no-border uppercase courier text-xs w-110-px">
                            001-720-0025
                        </td>
                        <td class="no-border text-xxs w-30-px">
                            {{-- NIT: --}}
                        </td>
                        <td class="no-border uppercase courier text-xs">
                            234578021
                        </td>
                    </tr>
                </table>
                <hr style="opacity:0">
                <table class="table-dticket-1" >
                    <tr>
                        <td class="text-xxs w-95-px" style="min-height:14px;height:14px;max-height:14px;">
                            {{-- Nº de Boleta: --}}
                        </td>
                        <td class="uppercase courier text-xs w-235-px" style="min-height:14px;height:14px;max-height:14px;">
                            {{ $payroll->code }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-xxs w-95-px" style="min-height:14px;height:14px;max-height:14px;">
                            {{-- Modalidad de Pago: --}}
                        </td>
                        <td class="uppercase courier text-xs" style="min-height:14px;height:14px;max-height:14px;">
                            ABONO EN CUENTA
                        </td>
                    </tr>
                    <tr>
                        <td class="text-xxs w-95-px" style="min-height:14px;height:14px;max-height:14px;">
                            {{-- Concepto de Pago: --}}
                        </td>
                        <td class="uppercase courier text-xs w-235-px" style="min-height:14px;height:14px;max-height:14px;">
                            {{ strlen($procedure->month->shortened) > 3 ? '' : 'PAGO DE HABERES ' }}{{ $procedure->month->shortened}} {{ $procedure->year }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-xxs w-95-px" style="min-height:14px;height:14px;max-height:14px;">
                            {{-- Nº Días Trab.: --}}
                        </td>
                        <td class="uppercase courier text-xs" style="min-height:14px;height:14px;max-height:14px;">
                            {{ $payroll->worked_days }}
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border text-xxs w-95-px" style="min-height:14px;height:14px;max-height:14px;">
                            {{-- Carnet de Identidad: --}}
                        </td>
                        <td class="uppercase courier text-xs w-120-px" style="min-height:14px;height:14px;max-height:14px;">{{ $payroll->ci_ext }}</td>
                    </tr>
                    <tr>
                        <td class="no-border text-xxs w-55-px" style="min-height:30px; max-height:30px; height:30px;" >
                            {{-- Nombre: --}} &nbsp;
                        </td>
                        <td colspan="6" class="uppercase courier text-xs">{{ $payroll->full_name }}</td>
                    </tr>
                    <tr>
                        <td class="no-border text-xxs w-95-px" style="min-height:14px;height:14px;max-height:14px;">
                            {{-- Nº de Cuenta: --}}
                        </td>
                        <td class="no-border uppercase courier text-xs w-120-px" style="min-height:14px;height:14px;max-height:14px;">{{ $payroll->account_number }}</td>
                    </tr>
                    <tr>
                        <td class="no-border text-xxs w-55-px" style="min-height:14px;height:14px;max-height:14px;">
                            {{-- Fecha Nac.: --}}
                        </td>
                        <td class="no-border uppercase courier text-xs w-110-px" style="min-height:14px;height:14px;max-height:14px;" >{{ $payroll->birth_date }}</td>
                    </tr>
                    {{-- <tr>
                        <td class="no-border text-xxs">Nº Item:</td>
                        <td class="uppercase courier text-xs">1871</td>
                    </tr> --}}
                    <tr>
                        <td class="no-border text-xxs w-95-px" style="min-height:14px;height:14px;max-height:14px;">
                            {{-- A.F.P.: --}}
                        </td>
                        <td class="no-border uppercase courier text-xs w-130-px" style="min-height:14px;height:14px;max-height:14px;">{{ $payroll->management_entity }}</td>
                    </tr>
                    <tr>
                        <td class="no-border text-xxs w-45-px" style="min-height:14px;height:14px;max-height:14px;">
                            {{-- N.U.A.: --}}
                        </td>
                        <td class="uppercase courier text-xs" style="min-height:14px;height:14px;max-height:14px;">{{ $payroll->nua_cua }}</td>
                    </tr>
                    <tr>
                        <td class="no-border text-xxs w-45-px align-top" style="min-height:50px; max-height:50px; height:50px;">
                            {{-- Cargo: --}}&nbsp;
                        </td>
                        <td class="uppercase courier text-xs leading-none align-top" colspan="6">{{ $payroll->position }}</td>
                    </tr>
                </table>
                {{-- <div style="min-height: 21px;max-height: 21px; height: 21px;padding:0;margin:0 0 0 3px; line-height:0">
                    <div class="text-xxs inline leading-none">Cargo: </div>
                    <div class="uppercase courier text-xs inline leading-none">{{ $payroll->position }}</div>
                </div> --}}
                <table class="border rounded m-t-10">
                    <tr>
                        <td class="text-sm text-left w-200-px px-5" style="font-weight:bold;">
                            {{-- Liquido Pagable en Bs.: --}}
                        </td>
                        <td class="text-lg text-right courier px-10" colspan="3">
                            {{ Util::formatMoney($payroll->payable_liquid) }}
                        </td>
                    </tr>
                </table>
                <div class="border rounded m-t-5 p-5 text-center" style="text-align:center; height:33px">
                    <img src="data:image/png;base64, {{ $payroll->code_image }}" alt="Red dot" style="height: 33px; width: 50%;" />
                </div>
            </div>
        </div>
    </div>

    @endforeach
</body>

</html>