<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>PLATAFORMA VIRTUAL - MUSERPOL </title>
  <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all" />
</head>
<body style="border: 0; border-radius: 0;">
  @for($it = 0; $it<2; $it++)
  <div style="height: 50px; max-height: 50px; min-height: 50px; margin-top: 0; padding-top: 0;"></div>
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
                  <td class="text-xs uppercase"> {{ Carbon::parse($supply_request->delivery_date)->format('d/m/Y') }} </td>
                </tr>
                <tr>
                  <td class="text-center bg-grey-darker text-xxs text-white">Nº </td>
                  <td class="text-xs uppercase"> {{ $supply_request->nro_solicitud }} </td>
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
              <th class="text-center border">ITEM</th>
              <th class="text-center border" >CANTIDAD</th>
              <th class="text-center border">UNIDAD</th>
              <th class="text-center border">DESCRIPCIÓN</th>
            </tr>
          </thead>
          <tbody class="table-striped">
            @foreach ($supply_request->subarticles as $i => $supply)
            <tr class="text-sm uppercase font-thin">
              <td class="text-center border">{{ ++$i }}</td>
              <td class="text-center border">{{ $supply['pivot']->amount }}</td>
              <td class="text-center border">{{ $supply['unit'] }}</td>
              <td class="text-center border">{{ $supply['description'] }}</td>
            </tr>
            @endforeach
            @for($i=sizeof($supply_request->subarticles)+1;$i<=15;$i++)
                <tr class="text-sm uppercase">
                    <td class="text-center border">{{ $i }}</td>
                    <td class="text-center border"></td>
                    <td class="text-center border"></td>
                    <td class="text-center border"></td>
                </tr>
            @endfor
          </tbody>
        </table>
        <table class="w-100"  border="1" frame="void" rules="all">
          <tbody class="">
            <tr class="" style="height: 100px; vertical-align: bottom;">
              <td class="text-center w-50 font-bold text-xxs">Solicitante</td>
              <td class="text-center w-50 font-bold text-xxs">Autorizado</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="text-xxxs" align="right"> {{ 'Impreso el '.date('d/m/Y H:i') }} </div>
  </div>
  @endfor
</body>
</html>