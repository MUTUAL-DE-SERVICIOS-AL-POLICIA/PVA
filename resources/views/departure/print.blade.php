<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL </title>
    {{-- <link rel="stylesheet" href="{{ asset('css/materialicons.min.css') }}" media="all" /> --}}
    <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all" />
</head>
<body style="border: 0;border-radius: 0;">
    <div class="page-break p-10" style="border-radius: 0.75em;border: 1px solid #22292f;">
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
                                    <td class="text-center bg-grey-darker text-xxs text-white">Tipo</td>
                                    <td class="text-xs uppercase"> {{ $departure->departure_reason->departure_type->name }} </td>
                                </tr>
                                <tr>
                                    <td class="text-center bg-grey-darker text-xxs text-white">Nº </td>
                                    <td class="text-xs uppercase"> {{ $departure->certificate->correlative.'/'.$departure->certificate->year }} </td>
                                </tr>
                                <tr>
                                    <td class="text-center bg-grey-darker text-xxs text-white">Fecha</td>
                                    <td class="text-xs uppercase"> {{ Carbon::parse($departure->created_at)->format('d/m/Y') }} </td>
                                </tr>
                            </tbody>
                        </table>
                
                </th>
            </tr>
            <tr><td colspan="3" style="border-bottom: 1px solid #22292f;"></td></tr>                  
        </table>
        <div class="block">
            <div class="font-semibold leading-tight text-md text-center">FORMULARIO DE SALIDA</div>
            <table class="table-info w-100 m-b-10">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm uppercase">
                        <td colspan='2' class="px-15 text-center">
                            DATOS DEL EMPLEADO
                        </td>
                    </tr>
                </thead>
                <tbody class="table-striped">
                    
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Nombre</td>
                        <td class="text-left uppercase px-5 py-3">
                            {{ $departure->contract->employee->first_name.' '.$departure->contract->employee->last_name.' '.$departure->contract->employee->mothers_last_name }}
                        </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Cargo</td>
                        <td class="text-left uppercase px-5 py-3"> {{ $departure->contract->position->name }} </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Oficina</td>
                        <td class="text-left uppercase px-5 py-3"> {{ $departure->contract->position->position_group->name }} </td>
                    </tr>
                </tbody>
            </table>
            <table class="table-info w-100 m-b-10">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm uppercase">
                        <td colspan='3' class="px-15 text-center">
                            DATOS DE LA SOLICITUD
                        </td>
                    </tr>
                </thead>
                <tbody class="table-striped">
                    
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Motivo</td>
                        <td colspan='2' class="text-left uppercase px-5 py-3"> {{ $departure->departure_reason->name }} </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Destino</td>
                        <td colspan='2' class="text-left uppercase px-5 py-3"> {{ $departure->destiny }} </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Horario</td>
                        <td class="text-left uppercase px-5 py-3">
                            Salida: {{ Carbon::parse($departure->departure_date)->format('d/m/Y').' '.Carbon::parse($departure->departure_time)->format('H:i') }}
                        </td>
                        <td class="text-left uppercase px-5 py-3">
                            Retorno: {{ Carbon::parse($departure->return_date)->format('d/m/Y').' '.Carbon::parse($departure->return_time)->format('H:i') }}
                        </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Observaciones</td>
                        <td colspan='2' class="text-left uppercase px-5 py-3"> {{ $departure->description }} </td>
                    </tr>
                </tbody>
            </table>
            <table class="table-info w-100 m-b-1">
                <tbody class="">
                    <tr class="" style="height: 190px; vertical-align: bottom;">
                        <td class="text-center w-30 font-bold">Solicitante</td>
                        <td class="text-center w-30 font-bold">Autorizado</td>
                        <td class="text-center w-30 font-bold">RRHH</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-xxxs" align="right"> {{ 'Impreso el '.date('d/m/Y H:i') }} </div>
    <br>
    <hr>
    <br>
    <div class="page-break p-10" style="border-radius: 0.75em;border: 1px solid #22292f;">
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
                                    <td class="text-center bg-grey-darker text-xxs text-white">Tipo</td>
                                    <td class="text-xs uppercase"> {{ $departure->departure_reason->departure_type->name }} </td>
                                </tr>
                                <tr>
                                    <td class="text-center bg-grey-darker text-xxs text-white">Nº </td>
                                    <td class="text-xs uppercase"> {{ $departure->certificate->correlative.'/'.$departure->certificate->year }} </td>
                                </tr>
                                <tr>
                                    <td class="text-center bg-grey-darker text-xxs text-white">Fecha</td>
                                    <td class="text-xs uppercase"> {{ Carbon::parse($departure->created_at)->format('d/m/Y') }} </td>
                                </tr>
                            </tbody>
                        </table>
                
                </th>
            </tr>
            <tr><td colspan="3" style="border-bottom: 1px solid #22292f;"></td></tr>                  
        </table>
        <div class="block">
            <div class="font-semibold leading-tight text-md text-center">FORMULARIO DE SALIDA</div>
            <table class="table-info w-100 m-b-10">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm uppercase">
                        <td colspan='2' class="px-15 text-center">
                            DATOS DEL EMPLEADO
                        </td>
                    </tr>
                </thead>
                <tbody class="table-striped">
                    
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Nombre</td>
                        <td class="text-left uppercase px-5 py-3">
                            {{ $departure->contract->employee->first_name.' '.$departure->contract->employee->last_name.' '.$departure->contract->employee->mothers_last_name }}
                        </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Cargo</td>
                        <td class="text-left uppercase px-5 py-3"> {{ $departure->contract->position->name }} </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Oficina</td>
                        <td class="text-left uppercase px-5 py-3"> {{ $departure->contract->position->position_group->name }} </td>
                    </tr>
                </tbody>
            </table>
            <table class="table-info w-100 m-b-10">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm uppercase">
                        <td colspan='3' class="px-15 text-center">
                            DATOS DE LA SOLICITUD
                        </td>
                    </tr>
                </thead>
                <tbody class="table-striped">
                    
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Motivo</td>
                        <td colspan='2' class="text-left uppercase px-5 py-3"> {{ $departure->departure_reason->name }} </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Destino</td>
                        <td colspan='2' class="text-left uppercase px-5 py-3"> {{ $departure->destiny }} </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Horario</td>
                        <td class="text-left uppercase px-5 py-3">
                            Salida: {{ Carbon::parse($departure->departure_date)->format('d/m/Y').' '.Carbon::parse($departure->departure_time)->format('H:i') }}
                        </td>
                        <td class="text-left uppercase px-5 py-3">
                            Retorno: {{ Carbon::parse($departure->return_date)->format('d/m/Y').' '.Carbon::parse($departure->return_time)->format('H:i') }}
                        </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Observaciones</td>
                        <td colspan='2' class="text-left uppercase px-5 py-3"> {{ $departure->description }} </td>
                    </tr>
                </tbody>
            </table>
            <table class="table-info w-100 m-b-1">
                <tbody class="">
                    <tr class="" style="height: 190px; vertical-align: bottom;">
                        <td class="text-center w-30 font-bold">Solicitante</td>
                        <td class="text-center w-30 font-bold">Autorizado</td>
                        <td class="text-center w-30 font-bold">RRHH</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-xxxs" align="right"> {{ 'Impreso el '.date('d/m/Y H:i') }} </div>
</body>
</html>