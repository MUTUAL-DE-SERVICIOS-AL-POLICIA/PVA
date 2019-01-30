<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL </title>
    {{-- <link rel="stylesheet" href="{{ asset('css/materialicons.min.css') }}" media="all" /> --}}
    <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all" />
</head>
<body style="border: 0;border-radius: 0;">
    @for($it=0; $it<2; $it++)
    <div class="page-break p-10" style="border-radius: 0.75em;border: 1px solid #22292f;">
        <table class="w-100 ">
            <tr>
                <th class="w-15 text-left no-padding no-margins align-middle">
                    <div class="text-center">
                        <img src="{{ public_path("/img/logo.png") }}" class="w-85">
                    </div>
                </th>
                <th class="w-50 align-top">
                    <div class="font-thin uppercase leading-tight text-sm" >
                        {{ 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"' }} <br>
                        {{ 'DIRECCIÓN DE ASUNTOS ADMINISTRATIVOS' }} <br>
                        {{ 'UNIDAD DE RECURSOS HUMANOS' }}
                    </div>
                </th>
                <th class="w-20 no-padding no-margins align-top">
                   
                        <table class="table-code no-padding no-margins">
                            <tbody>                            
                                <tr>
                                    <td class="text-center bg-grey-darker text-xxxs text-white">Tipo</td>
                                    <td class="text-xxs uppercase"> {{ $departure->departure_reason->departure_type->name }} </td>
                                </tr>
                                <tr>
                                    <td class="text-center bg-grey-darker text-xxxs text-white">Nº </td>
                                    <td class="text-xxs uppercase"> {{ $departure->certificate->correlative.'/'.$departure->certificate->year }} </td>
                                </tr>
                                <tr>
                                    <td class="text-center bg-grey-darker text-xxxs text-white">Fecha</td>
                                    <td class="text-xxs uppercase"> {{ Carbon::parse($departure->created_at)->format('d/m/Y') }} </td>
                                </tr>
                            </tbody>
                        </table>
                
                </th>
            </tr>
            <tr><td colspan="3" style="border-bottom: 1px solid #22292f;"></td></tr>                  
        </table>
        <div class="block">
            <div class="font-semibold leading-tight text-md text-center">SOLICITUD DE PERMISO</div>
            <table class="table-info w-100 m-b-10">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm uppercase">
                        <td colspan='2' class="px-15 text-center">
                            EMPLEADO
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
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Área</td>
                        <td class="text-left uppercase px-5 py-3"> {{ $departure->contract->position->position_group->name }} </td>
                    </tr>
                </tbody>
            </table>
            <table class="table-info w-100 m-b-10">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm uppercase">
                        <td colspan='4' class="px-15 text-center">
                            DETALLE
                        </td>
                    </tr>
                </thead>
                <tbody class="table-striped">
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Motivo</td>
                        <td colspan='3' class="text-left uppercase px-5 py-3"> {{ $departure->departure_reason->name }} </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Destino</td>
                        <td colspan='3' class="text-left uppercase px-5 py-3"> {{ $departure->destiny }} </td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Desde</td>
                        <td class="text-left uppercase w-30 px-5 py-3">
                            <span>{{ Carbon::parse($departure->departure_date)->format('d/m/Y') }}</span>
                            <span>&nbsp;</span>
                            <span>{{ Carbon::parse($departure->departure_time)->format('H:i') }}</span>
                        </td>
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Hasta</td>
                        <td class="text-left uppercase w-30 px-5 py-3">
                            <span>{{ Carbon::parse($departure->return_date)->format('d/m/Y') }}</span>
                            <span>&nbsp;</span>
                            <span>{{ Carbon::parse($departure->return_time)->format('H:i') }}</span>
                        </td>
                    </tr>
                    <tr class="text-sm" style="height: 50px">
                        <td class="text-left w-20 px-10 py-3 uppercase font-bold">Observaciones</td>
                        <td colspan='3' class="text-left uppercase px-5 py-3"> {{ $departure->description }} </td>
                    </tr>
                </tbody>
            </table>
            <table class="w-100" border="1" frame="void" rules="all" style="height: 180px;">
                <tbody>
                    <tr style="height: 100px; vertical-align: bottom;">
                        <td class="text-center w-33 font-bold text-xxs">Solicitante</td>
                        <td class="text-center w-33 font-bold text-xxs">Autorizado</td>
                        <td class="text-center w-33 font-bold text-xxs">RRHH</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <table class="w-100 text-xxxs">
        <tr>
            <td align="left">PLATAFORMA VIRTUAL ADMINISTRATIVA</td>
            <td align="right">{{ 'Impreso el '.date('d/m/Y H:i') }}</td>
        </tr>
    </table>
    @if($it == 0)
    <br>
    <hr>
    <br>
    @endif
    @endfor
</body>
</html>