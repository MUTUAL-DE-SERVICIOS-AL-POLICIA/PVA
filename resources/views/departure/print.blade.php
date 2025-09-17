<?php
use App\Company;
use \Carbon\Carbon;
use \Milon\Barcode\DNS2D;

$company = Company::select()->first();
$contract = $departure->employee->contract_in_date($departure->departure);
$consultant = $departure->employee->consultant();
$copies = 2;
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>PLATAFORMA VIRTUAL - MUSERPOL</title>
    <link rel="stylesheet" href="{{ public_path('/css/report-print.min.css') }}" media="all"/>
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
        margin-top: 12px;
        margin-bottom: 8px;
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
  @for($i = 1; $i <= $copies; $i++)
    <div class="page-break px-4">
      <table class="w-100">
        <tr>
          <th class="w-25 text-center align-middle">
            <div class="text-left">
              <img src="{{ public_path('/img/logo.png') }}" class="w-30">
            </div>
          </th>
          <th class="w-50 align-middle">
            <div class="font-thin uppercase leading-tight text-xs">
              <div>{{ mb_strtoupper($company->name) }} "{{ $company->shortened }}"</div>
              <div>DIRECCIÓN DE ASUNTOS ADMINISTRATIVOS</div>
              <div>UNIDAD DE RECURSOS HUMANOS</div>
            </div>
          </th>
          <th class="w-25 align-top">
            <table class="table-code text-xs">
              <tbody>
                <tr>
                  <td class="text-center bg-grey-darker text-white">Nº </td>
                  <td class="uppercase">{{ $departure->code }}</td>
                </tr>
                <tr>
                  <td class="text-center bg-grey-darker text-white">Fecha solicitud</td>
                  @php ($date = Carbon::parse($departure->created_at)->format('d/m/Y'))
                  <td class="uppercase">{{ $date }}</td>
                </tr>
                <tr>
                  <td class="text-center bg-grey-darker text-white">Tipo</td>
                  <td class="uppercase">{{ $departure->departure_reason->departure_group->name }}</td>
                </tr>
              </tbody>
            </table>
          </th>
        </tr>
        <tr>
          <td colspan="3" style="border-top: 1px solid #22292f;"></td>
        </tr>
      </table>
      <div class="block">
        <div class="font-semibold leading-tight text-sm text-center my-10 uppercase">
          @if ($departure->departure_reason->departure_group->name == 'COMISIÓN')
            {{ $departure->departure_reason->description }}
          @else
            {{ $departure->departure_reason->name }}
          @endif
        </div>
        <table class="table-info w-50 m-b-5 text-center uppercase" style="float: left; margin-left: 1px;">
          <tr class="bg-grey-darker text-xs text-white">
            <td>NOMBRE</td>
          </tr>
          <tr>
            @php ($name = $departure->employee->fullName())
            <td class="{{ Util::string_class_length($name, false) }} data-row py-5 text-sm">{{ $name }}</td>
          </tr>
        </table>
        <table class="table-info w-49 m-b-5 text-center uppercase" style="float: right; margin-left: 1px;">
          <tr class="bg-grey-darker text-xs text-white">
            <td class="w-50">DESDE</td>
            <td class="w-50">HASTA</td>
          </tr>
          <tr class="text-sm">
            <td class="w-50 py-5">
              <span>{{ Carbon::parse($departure->departure)->format('d/m/Y') }}</span>
              <span>&nbsp;</span>
              <span>{{ Carbon::parse($departure->departure)->format('H:i') }}</span>
            </td>
            <td class="w-50 py-5">
              <span>{{ Carbon::parse($departure->return)->format('d/m/Y') }}</span>
              <span>&nbsp;</span>
              <span>{{ Carbon::parse($departure->return)->format('H:i') }}</span>
            </td>
          </tr>
        </table>
        <table class="table-info w-100 m-b-10 uppercase text-center">
          <tr class="bg-grey-darker text-xs text-white">
            <td>CARGO</td>
          </tr>
          <tr>
            @if ($consultant)
              <td class="{{ Util::string_class_length($contract->consultant_position->name, false) }} data-row py-5">{{ $contract->consultant_position->name }}</td>
            @elseif($consultant == false)
              <td class="{{ Util::string_class_length($contract->position->name, false) }} data-row py-5">{{ $contract->position->name }}</td>
            @elseif($consultant == null)
              <td class="{{ Util::string_class_length($contract->assistant_position, false) }} data-row py-5">{{ $contract->assistant_position }}</td>
            @endif
          </tr>
          <tr class="bg-grey-darker text-xs text-white">
            <td>ÁREA</td>
          </tr>
          <tr>
            @if ($consultant)
              <td class="{{ Util::string_class_length($contract->consultant_position->position_group->name, false) }} data-row py-5">{{ $contract->consultant_position->position_group->name }}</td>
            @elseif($consultant == false)
              <td class="{{ Util::string_class_length($contract->position->position_group->name, false) }} data-row py-5">{{ $contract->position->position_group->name }}</td>
            @elseif($consultant == null)
              <td class="{{ Util::string_class_length($contract->position_group->name, false) }}
            @endif
          </tr>
        </table>
        <table class="table-info w-100 m-b-10 uppercase">
          <thead>
            <tr class="bg-grey-darker text-xs text-white text-center">
              <td>DETALLE</td>
            </tr>
          </thead>
          <tbody>
            <tr class="{{ Util::string_class_length($departure->description, false) }} text-left" style="height: 80px; max-height: 80px;">
              <td class="text-xs px-15">{{ $departure->description }}</td>
            </tr>
          </tbody>
        </table>
        <table class="w-100">
          <tbody>
            <tr class="align-bottom text-center font-bold text-xxs" style="height: 180px; vertical-align: bottom;">
              <td class="border rounded w-25">Solicitante</td>
              <td class="border rounded w-25">Autorizado</td>
              <td class="border rounded w-25">RRHH</td>
              <td class="border rounded w-25">Destino</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <table>
      <tr>
        <td class="text-xxxs" align="left">
          @if (env("APP_ENV") == "production")
            Este documento oficial queda invalidado al contener rayaduras, correcciones o raspones
          @else
            VERSIÓN DE PRUEBAS
          @endif
        </td>
        <td class="child" align="right">
          <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG(bcrypt($date . ' ' . gethostname() . ' ' . env('APP_URL')), 'PDF417') }}" alt="BARCODE!!!" style="height: 20px; width: 125px;" />
        </td>
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
