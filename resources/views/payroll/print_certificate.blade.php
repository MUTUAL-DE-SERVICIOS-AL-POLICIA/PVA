<?php
    //use \App\Helpers\Util;
    //use \App\Http\Controllers\Api\V1\PayrollController as Payroll;
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Certificado de haberes y aportes laborales</title>
        <style>
            <?php include public_path('css/payroll-print.css') ?>
        </style>
    </head>

    <body>
        <br><br><br><br><br><br>
        <div style="font-size: 8pt;" align="right">
            <p>DIRECCION DE ASUNTOS ADMINISTRATIVOS</p>
            <p>UNINDAD DE RECURSOS HUMANOS</p>
            <p>Certificado RR.HH. No. {{ $certificate->correlative }}/{{ $certificate->year }}</p>
            <p>Fecha {{ date('d') }} de {{ Carbon::parse(date('d/m/Y'))->formatLocalized('%B') }} de {{ date('Y') }}</p>
        </div>
        <div style="font-size: 16pt;font-weight: bold;margin-top:50px;" align="center">
            <p>CERTIFICADO DE HABERES Y APORTES LABORALES</p>
        </div>
        <div style="font-size: 13pt;margin-top: 15px;" align="justify">
            <p>El suscrito Jefe de la Unidad de Recursos Humanos, dependiente de la Dirección de Asuntos Administrativos de la Mutual de Servicios Al Policia - MUSERPOL, en uso de sus especificas funciones y atribuciones, en cuanto puede y el derecho le permite:</p>
        </div>
        <div style="font-size: 13pt;margin-top: 10px ;" align="justify">
            <p><span style="font-weight: bold">CERTIFICA:</span></p>
        </div>
        <div style="font-size: 13pt;margin-top: 10px" align="justify">
            <p>Que, revisado el archivo de Sueldos y Salarios de la Mutual de Servicios al Policia, conforme a la disponibilidad de información de la Base de datos que cursa en esta Unidad, se establece que el señor(a):</p>
        </div>
        <div style="font-size: 13pt;margin-top: 10px" align="justify">
            <p><span style="font-weight: bold">{{ Util::fullName($contract->employee) }}</span>, con C.I. <span style="font-weight: bold">{{ Util::ciExt($contract->employee) }}</span>, {{ ($contract->act==true)?'actualmente ocupa':'ocupó' }} el cargo de <span style="font-weight: bold">{{ $contract->position->name }}</span>
            </p>
        </div>
        <div style="font-size: 13pt;margin-top: 10px" align="justify">
            <p>Para efectos de trámites ADMINISTRATIVOS, percibió háberes y le efectuaron descuentos, de acuerdo al siguiente detalle:</p>
        </div>
        <br>
        <table style="font-size: 10pt">
            <thead>
                <tr>
                    <th width="60">MES Y AÑO</th>
                    <th width="100">CARGO</th>
                    <th>DIAS TRABAJA DOS</th>
                    <th>HABER BASICO</th>
                    <th>TOTAL GANADO</th>
                    <th>RENTA VEJEZ 10%</th>
                    <th>RIESGO COMUN 1,71%</th>
                    <th>COMISION 0,5%</th>
                    <th>APORTE SOLIDARIO DEL ASEGURADO 0,5%</th>
                    <th>APORTE NACIONAL SOLIDARIO 1% 5% 10%</th>
                    <th>OTROS DESC.</th>
                    <th>TOTAL DESC.</th>
                    <th>LIQUIDO PAGABLE</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payrolls as $data)
                    <tr>
                        <td style="font-size: 9pt">{{ $data->month_shortened }}-{{ $data->year }}</td>
                        <td style="font-size: 9pt">{{ $data->position }}</td>
                        <td>{{ $data->worked_days }}</td>
                        <td>{{ Util::format_number($data->base_wage) }}</td>
                        <td>{{ Util::format_number($data->quotable) }}</td>
                        <td>{{ Util::format_number($data->discount_old) }}</td>
                        <td>{{ Util::format_number($data->discount_common_risk) }}</td>
                        <td>{{ Util::format_number($data->discount_commission) }}</td>
                        <td>{{ Util::format_number($data->discount_solidary) }}</td>
                        <td>{{ Util::format_number($data->discount_national) }}</td>
                        <td></td>
                        <td>{{ Util::format_number($data->total_amount_discount_law) }}</td>
                        <td>{{ Util::format_number($data->net_salary) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>