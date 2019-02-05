<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>PLATAFORMA VIRTUAL - MUSERPOL </title>
  <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all" />
  <style>
    .data-row {
      height: 17px;
      min-height: 17px;
    }
    .description-row {
      height: 138px;
      min-height: 138px;
    }
    .scissors-rule {
      display: block;
      text-align: center;
      overflow: hidden;
      white-space: nowrap;
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
  @for($i = 0; $i < 3; $i++)
  <div class="page-break px-4" style="border-radius: 0.75em; border: 1px solid #22292f; padding-top: 3px;">
    <table class="w-100">
      <tr>
        <th class="w-15 text-left no-padding no-margins align-middle">
          <div class="text-center">
            <img src="{{ public_path("/img/logo.png") }}" class="w-65">
          </div>
        </th>
        <th class="w-50 align-top">
          <div class="font-thin uppercase leading-tight text-xs" >
            <div>MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"</div>
            <div>DIRECCIÓN DE ASUNTOS ADMINISTRATIVOS</div>
            <div>UNIDAD DE RECURSOS HUMANOS</div>
          </div>
        </th>
        <th class="w-20 no-padding no-margins align-top">
          <table class="table-code no-padding no-margins text-xxxs">
            <tbody>
              <tr>
                <td class="text-center bg-grey-darker text-white">Tipo</td>
                <td class="uppercase">{{ $departure->departure_reason->departure_type->name }}</td>
              </tr>
              <tr>
                <td class="text-center bg-grey-darker text-white">Nº </td>
                <td class="uppercase">{{ $departure->certificate->correlative.'/'.$departure->certificate->year }}</td>
              </tr>
              <tr>
                <td class="text-center bg-grey-darker text-white">Fecha</td>
                <td class="uppercase">{{ Carbon::parse($departure->created_at)->format('d/m/Y') }}</td>
              </tr>
            </tbody>
          </table>
        </th>
      </tr>
      <tr><td colspan="3" style="border-top: 1px solid #22292f;"></td></tr>
    </table>
    <div class="block">
      <div class="font-semibold leading-tight text-xs text-center m-b-5">SOLICITUD DE PERMISO</div>
      <table class="table-info w-50 m-b-5 text-center uppercase" style="float: left; margin-left: 1px;">
        <tr class="bg-grey-darker text-xxs text-white">
          <td>NOMBRE</td>
        </tr>
        <tr>
          @php ($name = Util::fullName($departure->contract->employee, 'uppercase'))
          <td class="{{ Util::departure_string_length($name) }} data-row">{{ $name }}</td>
        </tr>
        <tr class="bg-grey-darker text-xxs text-white">
          <td>CARGO</td>
        </tr>
        <tr>
          <td class="{{ Util::departure_string_length($departure->contract->position->name) }} data-row">{{ $departure->contract->position->name }}</td>
        </tr>
        <tr class="bg-grey-darker text-xxs text-white">
          <td>ÁREA</td>
        </tr>
        <tr>
          <td class="{{ Util::departure_string_length($departure->contract->position->position_group->name) }} data-row">{{ $departure->contract->position->position_group->name }}</td>
        </tr>
      </table>
      <table class="table-info w-49 m-b-5 text-center uppercase" style="float: right; margin-left: 1px;">
        <tr class="bg-grey-darker text-xxs text-white">
          <td class="w-50" colspan='2'>MOTIVO</td>
        </tr>
        <tr>
          <td class="w-50 text-xs " colspan='2'>{{ $departure->departure_reason->name }}</td>
        </tr>
        <tr class="bg-grey-darker text-xxs text-white">
          <td colspan='2'>DESTINO</td>
        </tr>
        <tr>
          <td class="{{ Util::departure_string_length($departure->destiny) }} data-row" colspan='2'>{{ $departure->destiny }}</td>
        </tr>
        <tr class="bg-grey-darker text-xxs text-white">
          <td class="w-50">DESDE</td>
          <td class="w-50">HASTA</td>
        </tr>
        <tr>
          <td class="w-50 text-xs">
            <span>{{ Carbon::parse($departure->departure_date)->format('d/m/Y') }}</span>
            <span>&nbsp;</span>
            <span>{{ Carbon::parse($departure->departure_time)->format('H:i') }}</span>
          </td>
          <td class="w-50 text-xs">
            <span>{{ Carbon::parse($departure->return_date)->format('d/m/Y') }}</span>
            <span>&nbsp;</span>
            <span>{{ Carbon::parse($departure->return_time)->format('H:i') }}</span>
          </td>
        </tr>
      </table>
      <table class="table-info w-100 m-b-10 uppercase">
        <thead>
          <tr class="bg-grey-darker text-xxs text-white text-center">
            <td>DETALLE</td>
          </tr>
        </thead>
        <tbody>
          <tr class="{{ Util::departure_string_length($departure->description) }} text-left" style="height: 30px;">
            <td>{{ $departure->description }}</td>
          </tr>
        </tbody>
      </table>
      <table class="w-100 description-row" border="1" frame="void" rules="all">
        <tbody>
          <tr style="height: 97px; vertical-align: bottom;">
            <td class="text-center w-33 font-bold text-xxxs">Solicitante</td>
            <td class="text-center w-33 font-bold text-xxxs">Autorizado</td>
            <td class="text-center w-33 font-bold text-xxxs">RRHH</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <table class="w-100 text-xxxxs">
    <tr>
      <td align="left">PLATAFORMA VIRTUAL ADMINISTRATIVA - MUSERPOL</td>
    </tr>
  </table>
  @if($i != 2)
  <div class="scissors-rule">
    <span>&#9986;</span>
  </div>
  @endif
  @endfor
</body>
</html>