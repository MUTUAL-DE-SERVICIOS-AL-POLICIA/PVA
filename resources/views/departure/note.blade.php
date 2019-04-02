<?php
use \Carbon\Carbon;
use \Milon\Barcode\DNS2D;

$contract = $departure->employee->contract_in_date($departure->departure);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL</title>
    <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all" />
    <style>
      body {
        border: 0;
      }
    </style>
  </head>
  <body>
    <div class="page-break px-4" style="padding-top: 3px;">
    <table class="w-100">
      <thead>
        <tr>
          <th class="w-50 text-left no-padding no-margins">
            <div class="text-left">
              <img src="{{ public_path("/img/logo.png") }}" class="w-50">
            </div>
          </th>
          <th class="w-50 text-right no-padding no-margins">
            <div class="text-right">
              <img src="{{ public_path("/img/escudo_bolivia.gif") }}" class="w-25">
            </div>
          </th>
        </tr>
        <tr>
          <td colspan="2" style="border-top: 1px solid gray;"></td>
        </tr>
      </thead>
    </table>
    <div class="text-right" style="margin-top: 1cm;">
      <div>
        {{ ucwords(mb_strtolower($contract->position->position_group->company_address->city->name)) }}, {{ Carbon::parse($departure->created_at)->isoFormat('LL') }}
      </div>
      <div>
        {{ $departure->cite }}
      </div>
    </div>
    <div class="text-left" style="margin-top: 1cm;">
      <div>
        Señor:
      </div>
      <div>
        Cnl. {{ $departure->director->fullName() }}
      </div>
      <div class="font-bold">
        {{ App\Position::first()->name }}
      </div>
      <div class="underline font-bold">
        Presente.-
      </div>
    </div>
    <div class="text-right font-bold uppercase" style="margin-top: 1cm;">
      REF.: SOLICITUD DE {{ $departure->departure_reason->name }}
    </div>
    <div class="text-left" style="margin-top: 1cm;">
      <div>
        De mi mayor consideración:
      </div>
    </div>
  </body>
</html>