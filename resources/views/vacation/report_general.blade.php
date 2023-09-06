<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ public_path('/css/report-print.min.css') }}" media="all" />
    <title>Reporte de Vacaciones</title>
    <style>
        body {
            border: 0;
            line-height: 2;
        }
    </style>
</head>

<body>
    <table class="w-100">
        <thead>
            <tr>
                <th class="w-33 text-left no-padding no-margins">
                    <div class="text-left">
                        <img src="{{ public_path('/img/logo.png') }}" class="w-40">
                    </div>
                </th>
                <th class="w-33 text-center no-padding no-margins">
                    <h3>REPORTE DE VACACIONES</h3>
                </th>
                <th class="w-33 text-right no-padding no-margins">
                    <div class="text-right">
                        <img src="{{ public_path('/img/escudo_bolivia.gif') }}" class="w-20">
                    </div>
                </th>
            </tr>
        </thead>
    </table>
    <table class="table-info w-100 m-b-10 uppercase text-center">
        <thead>
            <tr class="bg-grey-darker text-xs text-white">
                <th rowspan="2" width="3%">N°</th>
                <th rowspan="2" width="15%">Apellidos y Nombres</th>
                <th rowspan="2" width="10%">N° Carnet de Identidad</th>
                <th rowspan="2" width="10%">Fecha de Ingreso</th>
                <th rowspan="2" width="10%">N° de CAS</th>
                <th rowspan="2" width="10%">Fecha de emisión de CAS</th>
                <th width="15%" colspan="3">Años de Calificados</th>
                <th rowspan="2" width="10%">N° de días asignados según CAS</th>
                <th rowspan="2" width="10%">N° de días de vacaciones usadas</th>
                <th rowspan="2" width="7%">Saldo</th>
            </tr>
            <tr class="bg-grey-darker text-xs text-white">
                <th>Años</th>
                <th>Meses</th>
                <th>Días</th>
            </tr>
        </thead>
        <tbody class="text-xxs">
            @foreach ($employees as $employee)
                <tr>
                    <td class="data-row py-2">{{ $num = $num + 1 }}</td>
                    <td class="data-row py-2 text-left">{{ $employee->fullName }}</td>
                    <td class="data-row py-2">{{ $employee->identity_card }}</td>
                    <td class="data-row py-2">{{ $employee->addmission_date }}</td>

                    @if ($employee->activeCas)
                        <td class="data-row py-2">{{ $employee->activeCas->certification_number }}</td>
                        <td class="data-row py-2">{{ $employee->activeCas->issue_date }}</td>
                        <td class="data-row py-2">{{ $employee->activeCas->years }}</td>
                        <td class="data-row py-2">{{ $employee->activeCas->months }}</td>
                        <td class="data-row py-2">{{ $employee->activeCas->days }}</td>
                    @else
                        <td colspan="5">Sin registro de CAS</td>
                    @endif
                    <td class="data-row py-2">
                        @if ($employee->vacation_queues()->latest()->first())
                            {{ intval($employee->days_assigned) }}
                        @else
                            0
                        @endif
                    </td>
                    @if ($employee->vacation_queues->isEmpty())
                        <td colspan="2" class="data-row py-2">Sin datos</td>
                    @else
                        <td class="data-row py-2">
                            {{ round($employee->days_assigned - $employee->sum_rest_days, 1) }}
                        </td>
                        <td class="data-row py-2">
                            {{ round($employee->sum_rest_days, 1) }}
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
