
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL </title>
    {{-- <link rel="stylesheet" href="{{ asset('css/materialicons.min.css') }}" media="all" /> --}}
    <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all" />
</head>
<body>
    <div class="page-break">
        <table class="w-100 ">
            <tr>
                <th class="w-20 text-left no-padding no-margins align-middle">
                    <div class="text-center">
                        <img src="{{ public_path("/img/logo.png") }}" class="w-100">
                    </div>
                </th>
                <th class="w-50 align-top">
                    <span class="font-semibold uppercase leading-tight text-md" >
                        {{  'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"' }} <br>
                        {{  'DIRECCIÓN DE ASUNTOS ADMINISTRATIVOS' }} <br>
                        {{  'UNIDAD DE RECURSOS HUMANOS' }}
                    </span>
                </th>
                <th class="w-20 no-padding no-margins align-top">
                    <table class="table-code no-padding no-margins">
                        <tbody>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Oficina</td>
                                <td class="text-xxs font-hairline"> {{ (!isset($search->position_group))?'TODOS':$search->position_group->name }} </td>
                            </tr>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Empleado</td>
                                <td class="text-xxs font-hairline"> {{ (!isset($search->employee))?'TODOS':(Util::fullName($search->employee)) }} </td>
                            </tr>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Tipo</td>
                                <td class="text-xxs font-hairline"> {{ ($search->type == null)? 'TODOS':(($search->type == 1)?'Salidas':'Licencias') }} </td>
                            </tr>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Estado</td>
                                <td class="text-xxs font-hairline"> {{ ($search->state == null)? 'TODOS':(($search->state == true)?'APROBADO':'RECHAZADO') }} </td>
                            </tr>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Fechas</td>
                                <td class="text-xxs font-hairline"> {{ $search->start_date.' hasta '.$search->end_date }} </td>
                            </tr>
                        </tbody>
                    </table>
                </th>
            </tr>
            <tr><td colspan="3" style="border-bottom: 1px solid #22292f;"></td></tr>
        </table>
        <div class="block">
            <br>
            <div class="font-semibold leading-tight text-md text-center">REPORTE DE SALIDAS Y LICENCIAS</div>
            <table class="table-info w-100 m-b-10">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-xs uppercase">
                        <td class="px-15 text-center">#</td>
                        <td class="px-15 text-center">Oficina</td>
                        <td class="px-15 text-center">Cargo</td>
                        <td class="px-15 text-center">Nombre</td>
                        <td class="px-15 text-center">Tipo</td>
                        <td class="px-15 text-center">Razón</td>
                        <td class="px-15 text-center">Salida</td>
                        <td class="px-15 text-center">Retorno</td>
                        <td class="px-15 text-center">Estado</td>
                    </tr>
                </thead>
                <tbody class="table-striped">
                    @foreach($departures as $key => $departure)
                    <tr class="text-xs">
                        <td class="text-left uppercase px-5 py-3"> {{ ++$key }} </td>
                        <td class="text-left uppercase px-5 py-3"> {{ $departure->contract->position->position_group->name }} </td>
                        <td class="text-left uppercase px-5 py-3"> {{ $departure->contract->position->name }} </td>
                        <td class="text-left uppercase px-5 py-3"> {{ Util::fullName($departure->contract->employee) }} </td>
                        <td class="text-left uppercase px-5 py-3"> {{ $departure->departure_reason->departure_group->name }} </td>
                        <td class="text-left uppercase px-5 py-3"> {{ $departure->departure_reason->name }} </td>
                        <td class="text-left uppercase px-5 py-3"> {{ Carbon::parse($departure->departure_date)->format('d/m/Y').' '.Carbon::parse($departure->departure_time)->format('H:i') }} </td>
                        <td class="text-left uppercase px-5 py-3"> {{ Carbon::parse($departure->return_date)->format('d/m/Y').' '.Carbon::parse($departure->return_time)->format('H:i') }} </td>
                        <td class="text-left uppercase px-5 py-3"> {{ (is_null($departure->approved))? 'PENDIENTE': (($departure->approved == true)? 'APROBADO':'RECHAZADO') }} </td>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</body>
</html>