<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ public_path('/css/report-print.min.css') }}" media="all" />
    <title>Reporte de Colas de Vacaciones Anuladas</title>
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
                <th class="w-25 text-left no-padding no-margins">
                    <div class="text-left">
                        <img src="{{ public_path('/img/logo.png') }}" class="w-40">
                    </div>
                </th>
                <th class="w-50 text-center no-padding no-margins">
                    <h3>REPORTE DE ASIGNACIONES DE VACACIONES ANULADAS</h3>
                </th>
                <th class="w-25 text-right no-padding no-margins">
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
                <th width="3%">N°</th>
                <th width="15%">Apellidos y Nombres</th>
                <th width="10%">Carnet de Identidad</th>
                <th width="10%">Fecha de inicio</th>
                <th width="10%">Fecha para asignación</th>
                <th width="10%">Días asignados</th>
                <th width="10%">Días restantes</th>
                <th width="10%">Fecha de vencimiento</th>
                <th width="12%">Fecha de registro</th>
                <th width="12%">Motivo de anulación</th>
                <th width="10%">Fecha de anulación</th>
            </tr>
        </thead>
        <tbody class="text-xxs">
            @foreach ($vacation_queues as $vacation_queue)
                <tr>
                    <td class="data-row py-2">{{ $num = $num + 1 }}</td>
                    <td class="data-row py-2 text-left">{{ $vacation_queue->employee->fullName }}</td>
                    <td class="data-row py-2">{{ $vacation_queue->employee->identity_card }}</td>
                    <td class="data-row py-2">{{ $vacation_queue->start_date }}</td>
                    <td class="data-row py-2">{{ $vacation_queue->end_date }}</td>
                    <td class="data-row py-2">{{ $vacation_queue->days }}</td>
                    <td class="data-row py-2">{{ $vacation_queue->rest_days }}</td>
                    <td class="data-row py-2">{{ $vacation_queue->max_date }}</td>
                    <td class="data-row py-2">{{ $vacation_queue->created_at->format('Y-m-d') }}</td>
                    <td class="data-row py-2">{{ $vacation_queue->comment }}</td>
                    <td class="data-row py-2">{{ $vacation_queue->deleted_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
