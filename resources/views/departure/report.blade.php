<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @php($from = Carbon::parse($title->date->from)->ISOFormat('L'))
        @php($to = Carbon::parse($title->date->to)->ISOFormat('L'))
        <title>{{ $title->name }} {{ $from }} {{ $to }}</title>
    </head>

    <body>
        <div>
            <div class="header-left">
                <img id="header-image" src="{{ public_path().'/img/logo.png'}}">
            </div>
            <div class="header-center">
                <h2>
                    {{ $title->name }}
                </h2>
                <h3>DESDE {{ $from }} HASTA {{ $to }}</h3>
            </div>
        </div>

        <table align="center">
            <thead>
                <tr>
                    <th colspan="2" style="border-left: 1px solid white; border-top: 1px solid white; background-color: white;"></th>
                    <th colspan="7">SOLICITUD</th>
                </tr>
                <tr>
                    <th width="1%">N°</th>
                    <th width="20%">TRABAJADOR</th>
                    <th width="5%">TIPO</th>
                    <th width="10%">MOTIVO</th>
                    <th width="10%" colspan="2">DESDE</th>
                    <th width="10%" colspan="2">HASTA</th>
                    <th width="5%">ESTADO</th>
                </tr>
            </thead>
            <tbody>
            @if (count($departures) > 0)
                @foreach ($departures as $i => $departure)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td class="name">{{ $departure->employee->fullName() }}</td>
                        <td>{{ $departure->departure_reason->departure_group->name }}</td>
                        <td>{{ $departure->departure_reason->name }}</td>
                        @php ($from = Carbon::parse($departure->departure))
                        @php ($to = Carbon::parse($departure->return))
                        <td>{{ $from->ISOFormat('L') }}</td>
                        <td>{{ $from->format('H:i') }}</td>
                        <td>{{ $to->ISOFormat('L') }}</td>
                        <td>{{ $to->format('H:i') }}</td>
                        <td>
                            @if($departure->approved === true)
                                {{ 'APROBADO' }}
                            @elseif($departure->approved === false)
                                {{ 'RECHAZADO' }}
                            @elseif($departure->approved === null)
                                {{ 'PENDIENTE' }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </body>

</html>