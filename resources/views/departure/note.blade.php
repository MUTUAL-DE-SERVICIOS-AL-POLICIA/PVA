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
        line-height: 2;
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
        {{ ucwords(mb_strtolower($contract->position->position_group->company_address->city->name)) }}, {{ Carbon::parse($departure->created_at)->ISOFormat('LL') }}
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
        Cnl. {{ App\Position::with(['contracts' => function ($query) { $query->orderBy('created_at', 'ASC')->with('employee')->first(); }])->first()->contracts[0]->employee->fullName() }}
      </div>
      <div class="font-bold">
        {{ App\Position::first()->name }}
      </div>
      <div class="underline font-bold">
        Presente.-
      </div>
    </div>
    <div class="py-15">
    <div class="text-right font-bold uppercase" style="margin-top: 1cm;">
      REF.: SOLICITUD DE {{ $departure->departure_reason->name }}
    </div>
    </div>
    <div class="text-left" style="margin-top: 1cm;">
      <div>
        De mi mayor consideración:
      </div>
      <div class="py-15">
        <div class ="text-justify">
          <span>
              Mediante la presente, tengo a bien dirigirme a su Autoridad a objeto de solicitar se me autorice {{ $departure->departure_reason->name }}, para el día {{ Carbon::parse($departure->departure)->ISOFormat('LL') }}. Amparado por el Reglamento Interno de Personal de la Mutual de Servicio al Policía,
          </span>
            @switch ($departure->departure_reason->name)
              @case('LICENCIA CON GOCE DE HABERES')
                <span>
                  Capítulo III DE LAS LICENCIAS, Artículo 33 LICENCIA CON GOCE DE HABER, Inciso g) Para la resolución de asuntos de índole personal se otorgaran 2 días hábiles fraccionados en el transcurso de 1 año, previa autorización del inmediato superior, los cuales no podrán ser consecutivos (ni anteriores ni posteriores) a las vacaciones o feriados.
                </span>
              @break
            @endswitch
        </div>
      </div>
      <div class="py-15">
        Sin otro particular, saludo a Ud. con las consideraciones más distinguidas.
      </div>
      <div class="py-15">
        Atentamente,
      </div>
    </div>
  </body>
</html>