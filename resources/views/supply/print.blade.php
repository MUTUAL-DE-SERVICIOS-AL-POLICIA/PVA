<?php
use \Carbon\Carbon;
use \Milon\Barcode\DNS2D;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>PLATAFORMA VIRTUAL - MUSERPOL </title>
  <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all" />
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
  </style>
</head>
<body style="border: 0; border-radius: 0;">
  @for($it = 0; $it<2; $it++)
  {{-- <div style="height: 50px; max-height: 50px; min-height: 50px; margin-top: 0; padding-top: 0;"></div> --}}
    <div class="page-break p-10" style="border-radius: 0.75em; border: 1px solid #22292f;">
      <table class="w-100 uppercase">
        <tr>
          <th class="w-25 text-left no-padding no-margins align-middle">
            <div class="text-left">
              <img src="{{ public_path("/img/logo.png") }}" class="w-40">
            </div>
          </th>
          <th class="w-50 align-top">
            <div class="font-thin leading-tight text-xxs" >
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
        <tr><td colspan="3" style="border-bottom: 1px solid #22292f;"></td></tr>
      </table>
      <div class="block">
        <div class="font-semibold leading-tight text-xs text-center" style="margin-bottom: 5px">{{ $type == 'delivery' ? 'ENTREGA DE MATERIAL DE ALMACÉN' : 'SOLICITUD DE MATERIAL DE ALMACÉN' }}</div>
        <table class="table-info w-100 m-b-10 uppercase text-xs">
          <thead>
            <tr class="bg-grey-darker text-white font-light">
              <th class="text-center">ITEM</th>
              <th class="text-center">DESCRIPCIÓN</th>
              <th class="text-center">UNIDAD</th>
              <th class="text-center">SOLICITADO</th>
              @if ($type == 'delivery')
                <th class="text-center">ENTREGADO</th>
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
            @for($i=sizeof($supply_request->subarticles)+1;$i<=15;$i++)
              <tr>
                <td class="text-center" colspan="{{ $type == 'delivery' ? 5 : 4}}" >&nbsp;</td>
              </tr>
            @endfor
          </tbody>
        </table>
        <table class="w-100" border="1" frame="void" rules="all">
          <tbody class="">
            <tr class="" style="height: 150px; vertical-align: bottom;">
              <td class="text-center w-50 font-bold text-xxs">{{ $type == 'delivery' ? 'Recibí conforme' : 'Solicitante' }}</td>
              <td class="text-center w-50 font-bold text-xxs">{{ $type == 'delivery' ? 'Entregado por' : 'Autorizado' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <table>
      <tr>
        <td class="text-xxxs" align="left">
          @if (env("APP_ENV") != "production")
            VERSIÓN DE PRUEBAS
          @else
            Plataforma Virtual Administrativa - MUSERPOL
          @endif
        </td>
        <td class="child" align="right">
          <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG(bcrypt($date . ' ' . gethostname() . ' ' . env('APP_URL')), 'PDF417') }}" alt="BARCODE!!!" style="height: 20px; width: 125px;" />
        </td>
      </tr>
    </table>
  </div>
  @if($it == 0)
  <div class="scissors-rule">
    <span>&#9986;</span>
  </div>
  @endif
  @endfor
</body>
</html>
