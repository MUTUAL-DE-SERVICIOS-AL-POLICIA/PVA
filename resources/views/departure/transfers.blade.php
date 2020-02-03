<?php
use App\Company;
use \Milon\Barcode\DNS2D;
use \Carbon\Carbon;

$company = Company::select()->first();
$contract = $departure->employee->contract_in_date($departure->departure);
$consultant = $departure->employee->consultant();
$copies = 2;
$max_transfers = 12;
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
        margin-top: 9px;
        margin-bottom: 13px;
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
              <div>UNIDAD ADMINISTRATIVA</div>
            </div>
          </th>
          <th class="w-25 align-top">
            <table class="table-code text-xxs">
              <tbody>
                <tr>
                  <td class="text-center bg-grey-darker text-white">Nº </td>
                  <td class="uppercase">{{ $departure->id }}</td>
                </tr>
                <tr>
                  <td class="text-center bg-grey-darker text-white">Fecha comisión</td>
                  @php ($date = Carbon::parse($departure->departure)->format('d/m/Y'))
                  <td class="uppercase">{{ $date }}</td>
                </tr>
                <tr>
                  <td class="text-center bg-grey-darker text-white">Formulario</td>
                  <td class="uppercase">3</td>
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
        <div class="font-semibold leading-tight text-xs text-center m-b-5 uppercase">
          RECIBO DE MOVILIDAD
        </div>
        <table class="table-info w-50 m-b-5 text-center uppercase" style="float: left; margin-left: 1px;">
          <tr class="bg-grey-darker text-xxs text-white">
            <td>NOMBRE</td>
          </tr>
          <tr>
            <td class="{{ Util::string_class_length($departure->employee->fullName()) }} data-row py-5">{{ $departure->employee->fullName() }}</td>
          </tr>
          <tr class="bg-grey-darker text-xxs text-white">
            <td>MOTIVO</td>
          </tr>
          <tr>
            <td class="{{ Util::string_class_length($departure->description) }} data-row py-5">{{ $departure->description }}</td>
          </tr>
        </table>
        <table class="table-info w-49 m-b-5 text-center uppercase" style="float: right; margin-left: 1px;">
          <tr class="bg-grey-darker text-xxs text-white">
            <td class="w-50">DESDE</td>
            <td class="w-50">HASTA</td>
          </tr>
          <tr class="text-xs">
            <td class="w-50 py-5">
              @php ($fromDate = Carbon::parse($departure->departure)->format('d/m/Y'))
              @php ($toDate = Carbon::parse($departure->return)->format('d/m/Y'))
              @if ($fromDate != $toDate)
                <span>{{ $fromDate }}</span>
                <span>&nbsp;</span>
              @endif
              <span>{{ Carbon::parse($departure->departure)->format('H:i') }}</span>
            </td>
            <td class="w-50 py-5">
              @if ($fromDate != $toDate)
                <span>{{ Carbon::parse($departure->return)->format('d/m/Y') }}</span>
                <span>&nbsp;</span>
              @endif
              <span>{{ Carbon::parse($departure->return)->format('H:i') }}</span>
            </td>
          </tr>
          <tr class="bg-grey-darker text-xxs text-white">
            <td class="w-50" colspan='2'>DESTINO</td>
          </tr>
          <tr>
            <td class="{{ Util::string_class_length($departure->destiny) }} data-row py-5" colspan='2'>{{ $departure->destiny }}</td>
          </tr>
        </table>
        <table class="table-info w-100 m-b-10 uppercase text-xs">
          <thead>
            <tr>
              <th class="w-8 text-center bg-grey-darker text-white">Nº</th>
              <th class="w-41 text-center bg-grey-darker text-white border-left-white">DESDE</th>
              <th class="w-41 text-center bg-grey-darker text-white border-left-white">HASTA</th>
              <th class="w-10 text-center bg-grey-darker text-white">IMPORTE</th>
            </tr>
          </thead>
          <tbody class="table-striped">
            @foreach ($transfers as $n => $transfer)
            <tr class="font-thin">
              <td class="text-center">{{ ++$n }}</td>
              <td class="{{ Util::string_class_length($transfer['from']) }} text-center">{{ $transfer['from'] }}</td>
              <td class="{{ Util::string_class_length($transfer['to']) }} text-center">{{ $transfer['to'] }}</td>
              <td class="text-right px-15">{{ Util::formatMoney($transfer['cost']) }}</td>
            </tr>
            @endforeach
            @for ($n = sizeof($transfers) + 1; $n <= $max_transfers; $n++)
              <tr>
                <td colspan="3">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            @endfor
            @php ($f = new NumberFormatter('es', NumberFormatter::SPELLOUT))
            @php ($total = Util::formatMoney(array_sum(array_column($transfers, 'cost'))))
            <tr>
              <td class="text-center font-semibold bg-grey-lightest text-sm" colspan="3">TOTAL: {{ mb_strtoupper($f->format(intval($total))) }} {{ str_pad(round(fmod($total, 1), 1) * 100, 2, '0', STR_PAD_LEFT) }}/100 Bolivianos</td>
              <td class="text-right px-15 font-bold bg-grey-lightest text-sn-1">{{ $total }}</td>
            </tr>
          </tbody>
        </table>
        <table class="w-100">
          <tbody>
            <tr class="align-bottom text-center font-bold text-xxxs" style="height: 100px; vertical-align: bottom;">
              <td class="border rounded text-center w-33 font-bold text-xxxs">Recibí conforme</td>
              <td class="border rounded text-center w-33 font-bold text-xxxs">Autorizado</td>
              <td class="border rounded text-center w-33 font-bold text-xxxs">Entregué conforme</td>
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
    @if ($i < $copies)
    <div class="scissors-rule">
      <span>&#9986;</span>
    </div>
    @endif
  @endfor
  </body>
</html>
