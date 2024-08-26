<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL ADMINISTRATIVA - MUSERPOL </title>
    <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all"/>
    <style>
      .border-left-white {
        border-left: 1px solid white;
      }
    </style>
  </head>

  <body style="border: 0; border-radius: 0;">
    <table class="w-100 uppercase">
      <tr>
        <th class="w-15 text-left no-padding no-margins align-middle">
          <div class="text-left">
            <img src="{{ public_path("/img/logo.png") }}" class="w-50">
          </div>
        </th>
        <th class="w-70 align-top">
          <div class="font-hairline leading-tight text-xs" >
            <div>MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"</div>
            <div>DIRECCIÓN DE ASUNTOS ADMINISTRATIVOS</div>
            <div>UNIDAD DE RECURSOS HUMANOS</div>
          </div>
        </th>
        <th class="w-15 no-padding no-margins align-top">
          <table class="table-code no-padding no-margins text-xxxs uppercase">
            <tbody>
              <tr>
                  <td class="text-center bg-grey-darker text-white">Desde </td>
                  <td> {{ Carbon::parse($title->date->from)->ISOFormat('L') }} </td>
              </tr>
              <tr>
                <td class="text-center bg-grey-darker text-white">Hasta</td>
                <td> {{ Carbon::parse($title->date->to)->ISOFormat('L') }} </td>
              </tr>
              <tr>
                <td class="text-center bg-grey-darker text-white">Tipo</td>
                <td> {{ $title->type }} </td>
              </tr>
            </tbody>
          </table>
        </th>
      </tr>
    </table>
    <hr class="m-b-10" style="margin-top: 0; padding-top: 0;">
    <div class="block">
      <div class="font-semibold leading-tight text-center m-b-10 text-xs">{{ $title->name }}</div>
    </div>

    <table class="table-info w-100 m-b-10 uppercase text-xs">
      <thead>
        <tr>
          <th class="text-center bg-grey-darker text-white">N°</th>
          <th class="text-center bg-grey-darker text-white border-left-white">TRABAJADOR</th>
          <th class="text-center bg-grey-darker text-white border-left-white">TIPO</th>
          <th class="text-center bg-grey-darker text-white border-left-white">MOTIVO</th>
          <th class="text-center bg-grey-darker text-white border-left-white" colspan="2">DESDE</th>
          <th class="text-center bg-grey-darker text-white border-left-white" colspan="2">HASTA</th>
          <th class="text-center bg-grey-darker text-white border-left-white">ESTADO</th>
        </tr>
      </thead>
      <tbody>
      @if (count($departures) > 0)
        @foreach ($departures as $i => $departure)
          @php
            if ($departure->approved === true) {
              $text = 'APROBADO';
              $color_class = '';
            } elseif ($departure->approved === false) {
              $text = 'RECHAZADO';
              $color_class = 'bg-grey';
            } elseif ($departure->approved === null) {
              $text = 'PENDIENTE';
              $color_class = 'bg-grey-lightest';
            }
          @endphp
          <tr class="font-thin {{ $color_class }}">
            <td class="text-center">{{ ++$i }}</td>
            <td class="text-center">{{ $departure->employee->fullName("uppercase", "last_name_first") }}</td>
            <td class="text-center">{{ $departure->departure_reason->departure_group->name }}</td>
            <td class="text-center">{{ $departure->departure_reason->name }}</td>
            @php ($from = Carbon::parse($departure->departure))
            @php ($to = Carbon::parse($departure->return))
            <td class="text-center">{{ $from->ISOFormat('L') }}</td>
            <td class="text-center">{{ $from->format('H:i') }}</td>
            <td class="text-center">{{ $to->ISOFormat('L') }}</td>
            <td class="text-center">{{ $to->format('H:i') }}</td>
            <td class="text-center">{{ $text }}</td>
          </tr>
        @endforeach
      @endif
      </tbody>
    </table>
  </body>
</html>