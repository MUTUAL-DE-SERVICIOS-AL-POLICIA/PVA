<?php
use App\Helpers\Util;
$employee = $contract->employee;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ public_path('/css/report-print.min.css') }}" media="all" />
    <title>Reporte individual</title>
    <style>
        body {
            border: 0;
            line-height: 2;
        }
    </style>
</head>

<body>
    {{-- logos y título --}}
    <table class="w-100">
        <thead>
            <tr>
                <th class="w-50 text-left no-padding no-margins">
                    <div class="text-left">
                        <img src="{{ public_path('/img/logo.png') }}" class="w-40">
                    </div>
                </th>
                <th class="w-50 text-right no-padding no-margins">
                    <div class="text-right">
                        <img src="{{ public_path('/img/escudo_bolivia.gif') }}" class="w-20">
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="w-100 text-center no-padding no-margins">
                <td colspan="2"><h3>REPORTE DE VACACIONES INDIVIDUAL</h3></td>
            </tr>
        </tbody>
    </table>

    {{-- datos generales --}}
    <table class="table-info w-100 m-b-10 text-center uppercase" style="float: left; margin-left: 1px;">
        <tr>
            <th class="bg-grey-darker text-xs text-white" width="35%">Nombres y Apellidos</th>
            @php($name = $employee->fullName())
            <td class="{{ Util::string_class_length($name, false) }} data-row py-5 text-sm">{{ $name }}</td>
        </tr>
        <tr>
            <th class="bg-grey-darker text-xs text-white">Cargo</th>
            <td
                class="{{ Util::string_class_length($employee->last_contract()->position->name, false) }} data-row py-5">
                {{ $employee->last_contract()->position->name }}</td>
        </tr>
        <tr>
            <th class="bg-grey-darker text-xs text-white">Unidad Organizacional</th>
            <td
                class="{{ Util::string_class_length($employee->last_contract()->position->position_group->name, false) }} data-row py-5">
                {{ $employee->last_contract()->position->position_group->name }}</td>
        </tr>
        @if ($employee->activeCas)
            <tr>
                <th class="bg-grey-darker text-xs text-white">Fecha de emisión CAS</th>
                <td class="data-row py-2">{{ $employee->activeCas->issue_date }}</td>
            </tr>
            <tr>
                <th class="bg-grey-darker text-xs text-white">N° de días asignados según CAS</th>
                <td class="data-row py-2">{{ intval($employee->vacation_queues->last()->days) }}</td>
            </tr>
        @else
            <tr>
                <th class="bg-grey-darker text-xs text-white">Datos del CAS</th>
                <td rowspan="2">Sin registro.</td>
            </tr>
        @endif

    </table>
    <br><br>
    {{-- contenido --}}
    <table table class="table-info w-100 m-b-10 uppercase text-center">
        <thead>
            <tr class="bg-grey-darker text-xs text-white">
                <th width="4%">N°</th>
                <th width="30%">Concepto</th>
                <th width="16%">Fecha de solicitud</th>
                <th width="16%">Fecha inicial</th>
                <th width="16%">Fecha final</th>
                <th width="9%">N° de días</th>
                <th width="9%">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <?php $num = 1; ?>
            @foreach ($employee->departures()->where('departure_reason_id', 24)->get() as $departure)
                <tr>
                    <td>{{ $num }}</td>
                    <td>{{ $departure->description }}</td>
                    <td>{{ $departure->created_at }}</td>
                    <td>{{ $departure->departure }}</td>
                    <td>{{ $departure->return }}</td>
                    <td>A</td>
                    <td>B</td>
                </tr>
                <?php $num++; ?>
            @endforeach
        </tbody>
    </table>
</body>

</html>
