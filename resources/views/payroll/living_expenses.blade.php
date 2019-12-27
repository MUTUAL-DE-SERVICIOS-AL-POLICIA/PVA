<?php
use \App\Helpers\Util;
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all"/>
        <title>{{ $title->name }} {{ $procedure->year }}</title>
    </head>
    <body style="border: 0; border-radius: 0;">
        <table class="w-100 uppercase">
            <tr>
                <th class="w-25 text-left no-padding no-margins align-middle">
                    <div class="text-left">
                        <img src="{{ public_path("/img/logo.png") }}" class="w-40">
                    </div>
                </th>
                <th class="w-50 align-top">
                    <div class="font-hairline leading-tight text-xxs" >
                        <div>{{ $company->name }} "{{$company->shortened}}"</div>
                        <div>DIRECCIÓN DE ASUNTOS ADMINISTRATIVOS</div>
                        <div>UNIDAD DE RECURSOS HUMANOS</div>
                    </div>
                </th>
                <th class="w-25 no-padding no-margins align-top">
                    <table class="table-code no-padding no-margins text-xxxs uppercase">
                        <tbody>
                            <tr>
                                <td class="text-center bg-grey-darker text-white">Mes</td>
                                <td class="text-xxs"> {{ $procedure->month->name }} </td>
                            </tr>
                            <tr>
                                <td class="text-center bg-grey-darker text-white">Año</td>
                                <td class="text-xxs"> {{ $procedure->year }} </td>
                            </tr>
                        </tbody>
                    </table>
                </th>
            </tr>
        </table>
        <hr class="m-b-10" style="margin-top: 0; padding-top: 0;">
        <div class="block">
            <div class="font-semibold leading-tight text-sm text-center m-b-10">{{ $title->name }}</div>
            <table class="table-code w-100 m-b-10 text-xs">
                <tbody>
                    <tr>
                        <td class="uppercase w-20 text-center bg-grey-darker text-white font-light">Tipo Personal</td>
                        <td class="uppercase w-30 font-thin p-l-5 border">{{ $title->subtitle }}</td>
                        <td class="uppercase w-20 text-center bg-grey-darker text-white font-light" style="border-bottom: 1px solid white;">Nº de funcionarios</td>
                        <td class="uppercase w-30 font-thin p-l-5">{{ count($employees) }}</td>
                    </tr>
                    <tr>
                        <td class="uppercase w-20 text-center bg-grey-darker text-white font-light">Monto diario</td>
                        <td class="w-30 font-thin p-l-5 border">{{ $procedure->employer_contribution->living_expenses }} Bs.</td>
                        <td class="uppercase w-20 text-center bg-grey-darker text-white font-light">Monto total</td>
                        <td class="w-30 font-thin p-l-5">{{ Util::format_number($total_payment) }} Bs.</td>
                    </tr>
                </tbody>
            </table>
            <table class="table-info w-100 m-b-10 uppercase text-xs">
                <thead>
                    <tr>
                        <th class="w-10 text-center bg-grey-darker text-white">N°</th>
                        <th class="w-50 text-center bg-grey-darker text-white">NOMBRE</th>
                        <th class="w-10 text-center bg-grey-darker text-white">DÍAS TRABAJADOS</th>
                        <th class="w-10 text-center bg-grey-darker text-white">TOTAL A PAGAR</th>
                        <th class="w-20 text-center bg-grey-darker text-white">FIRMA</th>
                    </tr>
                </thead>
                <tbody class="table-striped">
                @php($total_payment = 0)
                @foreach ($employees as $i => $employee)
                    <tr class="font-thin" style="height: 45px;">
                        <td class="text-center">{{ ++$i }}</td>
                        <td class="text-left px-10">{{ $employee->full_name }}</td>
                        <td class="text-center">{{ $employee->worked_days }}</td>
                        <td class="text-center">{{ Util::format_number($employee->payment) }}</td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>