<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>PLATAFORMA VIRTUAL - MUSERPOL </title>
  <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all" />
</head>
<body style="border: 0; border-radius: 0;">
  <div style="height: 250px; max-height: 250px; min-height: 250px; margin-top: 0; padding-top: 0;"></div>
    <div class="page-break p-10" style="border-radius: 0.75em; border: 1px solid #22292f;">
      <table class="w-100">
        <tr>
          <th class="w-15 text-left no-padding no-margins align-middle">
            <div class="text-center">
              <img src="{{ public_path("/img/logo.png") }}" class="w-100">
            </div>
          </th>
          <th class="w-50 align-top">
            <span class="font-light uppercase leading-tight text-md" >
              {{  'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"' }} <br>
              {{  'DIRECCIÓN DE ASUNTOS ADMINISTRATIVOS' }} <br>
              {{  'UNIDAD ADMINISTRATIVA' }}
            </span>
          </th>
            <th class="w-20 no-padding no-margins align-top">
            <table class="table-code no-padding no-margins">
              <tbody>
                <tr>
                  <td class="text-center bg-grey-darker text-xxs text-white">Tipo</td>
                  <td class="text-xs uppercase"> SOL. ALMACENES </td>
                </tr>
                <tr>
                  <td class="text-center bg-grey-darker text-xxs text-white">Fecha</td>
                  <td class="text-xs uppercase"> {{ Carbon::now()->format('d/m/Y') }} </td>
                </tr>
                <tr>
                  <td class="text-center bg-grey-darker text-xxs text-white">Nº </td>
                  <td class="text-xs uppercase">  </td>
                </tr>
              </tbody>
            </table>
          </th>
        </tr>
      </table>
      <div class="block">
        <table class="table-info w-100 m-b-10">
          <thead>
            <tr class="bg-grey-darker font-medium text-white text-sm uppercase">
              <td colspan='6' class="px-15 text-center">
                PEDIDO INTERNO DE ALMACENES
              </td>
            </tr>
            <tr class="text-xs uppercase font-light">
              <th class="text-center border" rowspan="2">ITEM</th>
              <th class="text-center border text-xxs" colspan="2">CANTIDAD</th>
              <th class="text-center border" rowspan="2">UNIDAD</th>
              <th class="text-center border" rowspan="2">DESCRIPCIÓN</th>
              <th class="text-center border" rowspan="2">OBS.</th>
            </tr>
            <tr class="text-xxs uppercase font-light">
              <th class="text-center border">PEDIDA</th>
              <th class="text-center border">ENTREGADA</th>
            </tr>
          </thead>
          <tbody class="table-striped">
            @foreach ($supplies as $i => $supply)
            <tr class="text-sm uppercase font-thin">
              <td class="text-center border">{{ ++$i }}</td>
              <td class="text-center border">{{ $supply['request'] }}</td>
              <td class="text-center border"></td>
              <td class="text-center border">{{ $supply['unit'] }}</td>
              <td class="text-center border">{{ $supply['description'] }}</td>
              <td class="text-center border"></td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <table class="table-info w-100 m-b-1">
          <tbody class="">
            <tr class="" style="height: 100px; vertical-align: bottom;">
              <td class="text-center w-25 font-bold text-xxs">Solicitante</td>
              <td class="text-center w-25 font-bold text-xxs">Inmediato superior</td>
              <td class="text-center w-25 font-bold text-xxs">Autorizado</td>
              <td class="text-center w-25 font-bold text-xxs">Recibí conforme</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="text-xxxs" align="right"> {{ 'Impreso el '.date('d/m/Y H:i') }} </div>
  </div>
</body>
</html>