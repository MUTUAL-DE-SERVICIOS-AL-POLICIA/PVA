<?php
use App\Helpers\Util;
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
                <td colspan="2">
                    <h3>REPORTE DE VACACIONES INDIVIDUAL</h3>
                </td>
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
                <td class="data-row py-2">
                    @if ($employee->vacation_queues->last())
                        {{ intval($employee->vacation_queues->last()->days) }}
                    @else
                        0
                    @endif
                </td>
            </tr>
        @else
            <tr>
                <th class="bg-grey-darker text-xs text-white">Datos del CAS</th>
                <td rowspan="2">Sin registro</td>
            </tr>
        @endif

    </table>
    <br><br>
    {{-- contenido --}}
    <table table class="table-info w-100 m-b-10 uppercase text-center">
        <thead>
            <tr class="bg-grey-darker text-xs text-white">
                <th width="4%">N°</th>
                <th width="30%">Cite</th>
                <th width="16%">Fecha de solicitud</th>
                <th width="16%">Fecha inicial</th>
                <th width="16%">Fecha final</th>
                <th width="9%">N° de días</th>
                <th width="9%">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $vacation_days = 0;
            ?>
            @foreach ($data_report as $key => $report)
                @if ($report->type == 'vacation')
                    <tr class="bg-grey-lightest">
                        <td colspan="2"> CAS - Días: {{ intval($report->days) }} 
                            (<span class="text-xs">Periódo: {{ Carbon::parse($report->date_ini)->format('d-m-Y') }} al {{ Carbon::parse($report->date_end)->format('d-m-Y') }}</span>)
                        </td>
                        <td colspan="2"> Fecha de asignación: {{ Carbon::parse($report->date_end)->addDay()->format('d-m-Y') }}</td>
                        <td colspan="3"> Fecha de vencimiento: {{ $report->max_date }}</td>
                        <?php
                        $vacation_days = $vacation_days + $report->days;
                        ?>
                    </tr>
                @else
                    <?php
                        $num = $num+1;
                        $start = Carbon::parse($report->date_ini);
                        $end = Carbon::parse($report->date_end);
                        $vacation_days -= $report->departure_days;
                    ?>
                    <tr>
                        <td>{{ $num }}</td>
                        <td>{{ $report->cite }}</td>
                        <td>{{ Carbon::parse($report->date_request)->format('d-m-Y') }}</td>
                        <td>{{ Carbon::parse($report->date_ini)->format('d-m-Y') }}</td>
                        <td>{{ Carbon::parse($report->date_end)->format('d-m-Y') }}</td>
                        <td>
                            {{ fmod($report->departure_days, 1) == 0
                                ? intval($report->departure_days)
                                : number_format($report->departure_days, 1) }}
                        </td>
                        @if ($loop->last)
                            <td>{{ $vacation_days - $vacation_expires }}</td>
                        @else
                            <td>{{ $vacation_days }}</td>
                        @endif
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>

</html>
