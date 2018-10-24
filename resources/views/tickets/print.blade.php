<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boletas de Pago de {{ $procedure->month->name }} de {{ $procedure->year }}</title>
    <style>
        html,
        body {
            /* height: 1345px; */
            height: 1560px;
            /* background: #3b5998; */
            margin: 0;
            padding: 5px 0px;
            font-family: 'Arial', sans-serif;
        }
        .ticket{
            vertical-align: top;
            height: 33%;
            width: 100%;
            /* background:#3b5998; */
            margin: 0;padding: 0;
        }

        .main-left{
            vertical-align: top;
            height: 100%;
            width: 600px;
            /* background:red; */
            /* border-right: 2px dashed #3c3c3c; */
            border-right: 2px dashed transparent;
            display: inline-block;
            margin-right: 0;
            padding: 2px;
            padding-right: 20px;
        }
        .main-right{
            vertical-align: top;
            height: 100%;
            margin:0;
            width: 354px;
            display: inline-block;
            box-sizing: border-box;
            padding:0 0 0 20px;
            margin: 0;
            /* background:green; */
        }

        .courier{
            padding-top: 3px;
            font-family: 'Courier new', monospace;
            font-weight: 500 !important;
        }

        .border-bottom{
            /* border-bottom: 1px solid #5D6975; */
            border-bottom: 1px solid transparent;
        }
        .border-top{
            /* border-top: 1px solid #5D6975; */
            border-top: 1px solid transparent;
        }
         table { width: 100%; }
         .table-striped tr:nth-child(even){background:#F1F5F8}

        .table-info{ border-radius:.75em; overflow: hidden; border-spacing: 0;}

        .table-info thead tr td:first-child {
            border-radius:1em 0 0 0;
            /* border-left: 1px solid #5D6975; */
            border-left: 1px solid transparent;
        }

        .table-info thead tr td{
            /* border-left: 1px solid #5D6975; */
            border-left: 1px solid transparent;
            /* border-top: 1px solid #5D6975; */
            border-top: 1px solid transparent;
            /* border-bottom: 1px solid #5D6975; */
            border-bottom: 1px solid transparent;
        }

        .table-info thead tr td:last-child {
            border-radius:0 1em 0 0 ;
            /* border-right: 1px solid #5D6975; */
            border-right: 1px solid transparent;
        }

        .table-info thead tr td:only-child { border-radius:1em 1em 0 0 ; }

        .table-info table thead tr td:last-child{
            border-left: none;
        }
        .table-info tbody tr td {
            /* border-left: 1px solid #5D6975; */
            border-left: 1px solid transparent;
            padding: 1px 10px;
        }

        .table-info tbody tr:last-child td {
            /* border-bottom: 1px solid #5D6975; */
            border-bottom: 1px solid transparent;
            /* border-top: 1px solid #5D6975; */
            border-top: 1px solid transparent;
        }
        .table-info tbody tr td:last-child {
            /* border-right: 1px solid #5D6975; */
            border-right: 1px solid transparent;
        }
        .table-info tbody tr:last-child td:last-child {
            /* border-right: 1px solid #5D6975; */
            border-right: 1px solid transparent;
            /* border-bottom:1px solid #5D6975; */
            border-bottom:1px solid transparent;
        }
        .table-info tbody tr:last-child td:first-child {
            border-radius: 0 0 0 1em;
            /* border-bottom:1px solid #5D6975; */
            border-bottom:1px solid transparent;
        }
        .table-info tbody tr:last-child td:last-child {
            border-radius: 0 0 1em 0;
            /* border-bottom:1px solid #5D6975; */
            border-bottom:1px solid transparent;
            }
        .table-info tbody tr:last-child td:only-child {
            /* border-bottom:1px solid #5D6975; */
            border-bottom:1px solid transparent;
            border-radius: 0 0 1em 1em;
        } 
        .table-collapse{ border-collapse: collapse; }
        .border-grey-darker{
            /* border-color:#5D6975; */
            border-color:transparent;
            border-style: solid; border-width: 1px;
        }
        .border{
            /* border-color: #5D6975; */
            border-color: transparent;
            border-style: solid;
            border-width: 1px;
        }
        .border1{
            border-color: #5D6975;
            border-style: solid;
            border-width: 1px;
        }
        .border-b{ border-bottom: 1px solid #fff; } .border-b{ border-color: #22292f; border-style: solid; border-bottom-width:
        1px, } .border-t{ border-color: #22292f; border-style: solid; border-top-width: 1px, } .border-r{ border-color: #22292f;
        border-style: solid; border-right-width: 1px, } .border-l{ border-color: #22292f; border-style: solid; border-left-width:
        1px; } .inline{ display: inline; } .inline-block{ display: inline-block; } .block{ display: block; } .table{ display: table;
        } .table-row{ display: table-row; } 
        .text-left{
            text-align: left;
            }
         .text-center {text-align: center;} .text-right {text-align:
        right;} .text-justify {text-align: justify;}
         .w-10{ width: 10%; }
         .w-15{ width: 15%; }
        .w-20{ width: 20%; }
        .w-25{ width:25%; }
        .w-30{ width: 30%; }
        .w-33{ width: 33%; }
        .w-35{ width: 35%; }
        .w-38{ width: 38%; }
        .w-39{ width: 39.5%; }
        .w-40{ width: 40%; }
        .w-45{ width: 45%; }
        .w-50{ width: 50%; }
        .w-60{ width: 60%; }
        .w-75{ width: 75%; }
        .w-100{ width: 100%; }


        .w-30-px{
            min-width:30px;
            width:30px;
            max-width:30px;
        }
        .w-45-px{
            min-width:45px;
            width:45px;
            max-width:45px;
        }
        .w-55-px{
            min-width:55px;
            width:55px;
            max-width:55px;
        }
        .w-60-px{
            min-width:60px;
            width:60px;
            max-width:60px;
        }
        .w-95-px{
            min-width:95px;
            width:95px;
            max-width:95px;
        }
        .w-100-px{
            min-width:100px;
            width:100px;
            max-width:100px;
        }
        .w-110-px{
            min-width:110px;
            width:110px;
            max-width:110px;
        }
        .w-120-px{
            min-width:120px;
            width:120px;
            max-width:120px;
        }
        .w-130-px{
            min-width:130px;
            width:130px;
            max-width:130px;
        }
        .w-140-px{
            min-width:140px;
            width:140px;
            max-width:140px;
        }
        .w-150-px{
            min-width:150px;
            width:150px;
            max-width:150px;
        }
        .w-160-px{
            min-width:160px;
            width:160px;
            max-width:160px;
        }
        .w-170-px{
            min-width:170px;
            width:170px;
            max-width:170px;
        }
        .w-180-px{
            min-width:180px;
            width:180px;
            max-width:180px;
        }
        .w-190-px{
            min-width:190px;
            width:190px;
            max-width:190px;
        }
        .w-200-px{
            min-width:200px;
            width:200px;
            max-width:200px;
        }
        .w-235-px{
            min-width:235px;
            width:235px;
            max-width:235px;
        }
        .mw-100{ max-width: 100%; }
        .p-10{ padding: 10px; }
        .p-5{ padding: 5px; }
        .py-15 { padding-top: 15px; padding-bottom: 15px;}
        .py-10 { padding-top: 10px; padding-bottom: 10px;}
        .py-5 { padding-top: 5px; padding-bottom: 5px;}
        .py-4 { padding-top: 4px; padding-bottom: 4px;}
        .py-3 { padding-top: 3px; padding-bottom: 3px; }
        .py-2 { padding-top: 2px; padding-bottom: 2px; }
        .px-15 { padding-left: 15px; padding-right: 15px; }
        .px-10 { padding-left: 10px; padding-right: 10px; }
        .px-5 { padding-left: 5px; padding-right: 5px; }
        .px-4 { padding-left: 4px; padding-right: 4px; }
        .px-3 { padding-left: 3px; padding-right: 3px; }
        .px-2 { padding-left: 2px; padding-right: 2px; }
        .mx-10{ margin-top: 10px; margin-bottom: 10px; }
        .mx-5{ margin-top: 5px; margin-bottom: 5px; }
        .m-b-5{ margin-bottom: 5px; }
        .m-b-6{ margin-bottom: 10px; }
        .m-b-10{ margin-bottom: 10px; } 
        .m-b-15{ margin-bottom: 15px; }
        .m-b-20{ margin-bottom: 20px; }
        .m-b-25{ margin-bottom: 25px; }
        .m-b-50{ margin-bottom: 50px; }
        .m-t-5{ margin-top: 5px; }
        .m-t-10{ margin-top: 10px; }
        .m-t-15{ margin-top: 15px; }
        .m-t-20{ margin-top: 20px; }
        .m-t-21{ margin-top: 21px; }
        .m-t-22{ margin-top: 22px; }
        .m-t-23{ margin-top: 23px; }
        .m-t-25{ margin-top: 25px; }
        .m-t-27{ margin-top: 27px; }
        .m-t-28{ margin-top: 28px; }
        .m-t-30{ margin-top: 30px; }
        .m-t-35{ margin-top: 35px; }
        .m-t-48{ margin-top: 48px; }
        .m-t-50{ margin-top: 50px; }

        .m-l-5{ margin-left: 5px; }
        .m-l-10{ margin-left: 10px; }
        .m-l-15{ margin-left: 15px; }
        .m-l-20{ margin-left: 20px; }
        .m-l-25{ margin-left: 25px; }
        .m-l-30{ margin-left: 30px; }
        .m-l-35{ margin-left: 35px; }
        .m-l-50{ margin-left: 50px; }
        .m-r-5{ margin-right: 5px; }
        .m-r-10{ margin-right: 10px; }
        .m-r-15{ margin-right: 15px; }
        .m-r-20{ margin-right: 20px; }
        .m-r-25{ margin-right: 25px; }
        .m-r-30{ margin-right: 30px; }
        .m-r-35{ margin-right: 35px; }
        .m-r-50{ margin-right: 50px; }

        .pin-t{top: 0;} .pin-r{right: 0;} .pin-b{bottom: 0;} .pin-l{left: 0;} .pin-y{
        top: 0; bottom: 0; } .pin-x{ right: 0; left: 0; } .pin{ top: 0; right: 0; bottom: 0; left: 0; } .pin-none{ top: auto; right:
        auto; bottom: auto; left: auto; } .bg-grey-darker{background-color: #5D6975;} .bg-grey-lightest {background-color: #dae1e7;}
        .font-hairline { font-weight: 100; } .font-thin { font-weight: 200; } .font-light { font-weight: 300; } .font-normal { font-weight:
        400; } .font-medium { font-weight: 500; } .font-semibold { font-weight: 600; } .font-bold { font-weight: 700; } .font-extrabold
        { font-weight: 800; } .font-black { font-weight: 900; } .uppercase { text-transform: uppercase; } .lowercase { text-transform:
        lowercase; } .capitalize { text-transform: capitalize; } .normal-case { text-transform: none; } .underline { text-decoration:
        underline; } .line-through { text-decoration: line-through; } .no-underline { text-decoration: none; } .text-black{color:
        #22292f;} .text-white{color: #ffffff;}
        .text-xxxs {font-size: 8px;}
        .text-xxs {font-size: 10px;}
        .text-xxms {font-size: 11px;}
        .text-xs {font-size: 12px;}
        .text-sm {font-size: 14px;}
        .text-smm {font-size: 15px;}
        .text-base {font-size: 16px;}
        .text-lg {font-size: 18px;}
        .text-xl {font-size: 20px;}
        .text-2xl{font-size: 24px;}
        .text-3xl {font-size: 30px;}
        .text-4xl {font-size: 36px;}
        .text-5xl {font-size: 3rem;}
        .rounded { border-top-left-radius:
        .7rem; border-top-right-radius: .7rem; border-bottom-right-radius: .7rem; border-bottom-left-radius: .7rem; } .rounded-t
        { border-top-left-radius: .7rem; border-top-right-radius: .7rem; } .rounded-tl{ border-top-left-radius: .7rem; } .rounded-tr{
        border-top-right-radius: .7rem; }
        .leading-none {
             line-height: 1;
        }

        .leading-tight {line-height: 1.25;}
        .leading-normal {line-height: 1.5;}
        .leading-loose {line-height: 2; border: 0px;padding-top:10px}

         .list-roman{ list-style-type:upper-roman; } .align-baseline{
        vertical-align: baseline;} .align-top{ vertical-align: top;} .align-middle{ vertical-align: middle;} .align-bottom{ vertical-align:
        bottom;} .align-text-top{ vertical-align: text-top;} .align-text-bottom{ vertical-align: text-bottom;}
        .float-left{
            float: left;
        }
        .float-right{
            float: right;
        }
        .absolute{
            position: absolute;
        }


        .table-ticket-1 {
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 10px;
        }
        .table-ticket-1 tr, .table-ticket-1 tr td{
            margin:0;padding: 0
        }
        .table-background{
               /* background-image: url("{{ asset('images/logo.jpg') }}"); */
            background-repeat:no-repeat;
            background-position: center center;
            background-size: auto
        }
        .page {
            overflow: hidden;
            page-break-after: always;
        }
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
                    <td class="uppercase courier text-xs w-235-px">PAGO DE HABERES {{ $procedure->month->shortened}} {{ $procedure->year }}</td>
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
                            PAGO DE HABERES {{ $procedure->month->shortened}} {{ $procedure->year }}
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