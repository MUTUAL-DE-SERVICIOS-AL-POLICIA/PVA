<?php
use \Carbon\Carbon;
use \Milon\Barcode\DNS2D;

$max_requests = 14;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>PLATAFORMA VIRTUAL ADMINISTRATIVA - MUSERPOL </title>
  <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all"/>
  <style>
    .scissors-rule {
      display: block;
      text-align: center;
      overflow: hidden;
      white-space: nowrap;
      margin-top: 6px;
      margin-bottom: 17px;
    }
    .scissors-rule > span {
      position: relative;
      display: inline-block;
    }
    .scissors-rule > span:before,
    .scissors-rule > span:after {
      content: "";
      position: absolute;
      top: 50%;
      width: 9999px;
      height: 1px;
      background: white;
      border-top: 1px dashed black;
    }
    .scissors-rule > span:before {
      right: 100%;
      margin-right: 5px;
    }
    .scissors-rule > span:after {
      left: 100%;
      margin-left: 5px;
    }
    .border-left-white {
      border-left: 1px solid white;
    }
    .p-l-5 {
      padding-left: 5px;
    }
  </style>
</head>
<body style="border: 0; border-radius: 0;">
  @for($it = 0; $it<2; $it++)
    <table class="w-100 uppercase">
      <tr>
        <th class="w-25 text-left no-padding no-margins align-middle">
          <div class="text-left">
            <img src="{{ public_path("/img/logo.png") }}" class="w-40">
          </div>
        </th>
        <th class="w-50 align-top">
          <div class="font-hairline leading-tight text-xxs" >
            <div>MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"</div>
            <div>DIRECCIÓN DE ASUNTOS ADMINISTRATIVOS</div>
            <div>UNIDAD ADMINISTRATIVA</div>
          </div>
        </th>
        <th class="w-25 no-padding no-margins align-top">
          <table class="table-code no-padding no-margins text-xxxs uppercase">
            <tbody>
              <tr>
                  <td class="text-center bg-grey-darker text-white">Nº </td>
                  <td class="text-xxs"> {{ $supply_request->nro_solicitud }} </td>
              </tr>
              <tr>
                <td class="text-center bg-grey-darker text-white">Fecha</td>
                @php ($date = ($type == 'delivery') ? $supply_request->delivery_date : $supply_request->created_at)
                @php ($date = Carbon::parse($date)->format('d/m/Y'))
                <td class="text-xxs"> {{ $date }} </td>
              </tr>
            </tbody>
          </table>
        </th>
      </tr>
    </table>
    <hr class="m-b-10" style="margin-top: 0; padding-top: 0;">
    <div class="block">
      <div class="font-semibold leading-tight text-sm text-center m-b-10">{{ $type == 'delivery' ? 'ENTREGA DE MATERIAL DE ALMACÉN' : 'SOLICITUD DE MATERIAL DE ALMACÉN' }}</div>

      <table class="table-code w-100 m-b-10 uppercase text-xs">
        <tbody>
          <tr>
            <td class="w-10 text-center bg-grey-darker text-white font-light">Nombre</td>
            <td class="w-90 font-thin p-l-5">{{ $supply_request->employee->name }}</td>
          </tr>
          <tr>
            <td class="w-10 text-center bg-grey-darker text-white font-light">Cargo</td>
            <td class="w-90 font-thin p-l-5">{{ $supply_request->employee->title }}</td>
          </tr>
        </tbody>
      </table>

      <table class="table-info w-100 m-b-10 uppercase text-xs">
        <thead>
          <tr>
            <th class="text-center bg-grey-darker text-white">ITEM</th>
            <th class="text-center bg-grey-darker text-white border-left-white">DESCRIPCIÓN</th>
            <th class="text-center bg-grey-darker text-white border-left-white">UNIDAD</th>
            <th class="text-center bg-grey-darker text-white {{ $type == 'delivery' ? 'border-left-white' : '' }}">SOLICITADO</th>
            @if ($type == 'delivery')
              <th class="text-center bg-grey-darker text-white border-left-white">ENTREGADO</th>
            @endif
          </tr>
        </thead>
        <tbody class="table-striped">
          @foreach ($supply_request->subarticles as $i => $supply)
          <tr class="font-thin">
            <td class="text-center">{{ ++$i }}</td>
            <td class="text-center">{{ $supply['description'] }}</td>
            <td class="text-center">{{ $supply['unit'] }}</td>
            <td class="text-center">{{ $supply['pivot']->amount }}</td>
            @if ($type == 'delivery')
              <td class="text-center">{{ $supply['pivot']->total_delivered }}</td>
            @endif
          </tr>
          @endforeach
          @for($i = sizeof($supply_request->subarticles) + 1; $i <= $max_requests; $i++)
            <tr>
              <td class="text-center" colspan="{{ $type == 'delivery' ? 5 : 4}}" >&nbsp;</td>
            </tr>
          @endfor
        </tbody>
      </table>

      <table class="w-100">
        <tbody>
          <tr class="align-bottom text-center font-bold text-xxxs" style="height: 120px; vertical-align: bottom;">
            <td class="border rounded {{ $type != 'delivery' ? 'w-33' : 'w-50' }}">{{ $type == 'delivery' ? 'Recibí conforme' : 'Solicitante' }}</td>
            <td class="border rounded {{ $type != 'delivery' ? 'w-33' : 'w-50' }}">{{ $type == 'delivery' ? 'Entregado por' : 'Inmediato Superior' }}</td>
            @if ($type != 'delivery')
              <td class="border rounded w-33">Autorizado</td>
            @endif
          </tr>
        </tbody>
      </table>
    </div>
    <table>
      <tr>
        <td class="text-xxxs" align="left">
          @if (env("APP_ENV") == "production")
            PLATAFORMA VIRTUAL ADMINISTRATIVA
          @else
            VERSIÓN DE PRUEBAS
          @endif
        </td>
        <td class="child" align="right">
          <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG(bcrypt($date . ' ' . gethostname() . ' ' . env('APP_URL')), 'PDF417') }}" alt="BARCODE!!!" style="height: 22px; width: 125px;" />
        </td>
      </tr>
    </table>
    @if($it == 0)
      <div class="scissors-rule">
        <span>&#9986;</span>
      </div>
    @endif
  @endfor
</body>
</html>
