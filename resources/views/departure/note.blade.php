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
      </thead>
    </table>
    <hr width="100%">
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
    @if($departure->departure_reason->name != 'CON GOCE DE HABERES' && $departure->departure_reason->name != 'SIN GOCE DE HABERES')
      @php ($addon = 'POR')
    @else
      @php ($addon = '')
    @endif
    <div class="text-right font-bold uppercase" style="margin-top: 1cm;">
      REF.: SOLICITUD DE LICENCIA {{ $addon }} {{ $departure->departure_reason->name }}
    </div>
    </div>
    <div class="text-left" style="margin-top: 1cm;">
      <div class="py-15">
        De mi mayor consideración:
      </div>
      <div class="py-15">
        <div class ="text-justify">
          <span>
              Mediante la presente, tengo a bien dirigirme a su Autoridad a objeto de solicitar se me autorice la LICENCIA {{ $addon }} {{ $departure->departure_reason->name }}, amparado por
              @if ($departure->departure_reason->name != 'EXAMEN DE PRÓSTATA' && $departure->departure_reason->name != 'EXAMEN DE COLON')
                el Reglamento Interno de Personal de la Mutual de Servicio al Policía,
              @endif
          </span>
            @switch ($departure->departure_reason->name)
              @case('CON GOCE DE HABERES')
                <span>
                  DEL REGIMEN DE ASISTENCIA, Capítulo III DE LAS LICENCIAS, Artículo 33 LICENCIA CON GOCE DE HABER, Inciso g) Para la resolución de asuntos de índole personal se otorgaran 2 días hábiles fraccionados en el transcurso de 1 año, previa autorización del inmediato superior, los cuales no podrán ser consecutivos (ni anteriores ni posteriores) a las vacaciones o feriados.
                </span>
              @break
              @case('SIN GOCE DE HABERES')
                <span>
                  DEL REGIMEN DE ASISTENCIA, Capítulo III DE LAS LICENCIAS, Artículo 34 LICENCIA SIN GOCE DE HABER, Incisos:
                  <ol type="a">
                    <li>Cuando la servidora o servidor público no pueda acceder a una licencia con cargo a vacación.</li>
                    <li>Por la asistencia a cursos de capacitación, Post Grado como participante particular (sin patrocinio de MUSERPOL).</li>
                    <li>Por motivos de estudio o realización de un trabajo de grado, hasta tres días calendario hasta antes de su examen o defensa del trabajo de grado, previa certificación de la autoridad universitaria competente.</li>
                    <li>Por motivo de salud (tratamientos a la o el servidor o miembro de su familia que no sean pagados por el Seguro de Social) por periodos no mayores a treinta días.</li>
                    <li>Por el tiempo de Cumplimiento del Servicio Militar, conforme a Ley.</li>
                    <li>Por otros motivos no incluidos en los incisos anteriores, por periodos no mayores a cinco días.</li>
                  </ol>
                  Toda autorización para licencia sin goce de haberes deberá ser puesta a consideración y aprobación del Director General Ejecutivo para posteriormente ser remitida a la Unidad de Recursos Humanos para su cómputo. Las licencias sin goce de haberes podrán ser otorgados por un tiempo máximo de 10 días hábiles. En caso de que se requiera un tiempo mayor la autorización podrá ser aprobada por la Unidad de Recursos Humanos por un plazo máximo de 10 días adicionales. La Máxima Autoridad Ejecutiva, es quien autorizará la licencia especial sin goce de haberes a partir del día 21 del mes con duración máxima de tres meses. La autorización deberá ser respaldada mediante Resolución Administrativa expresa.
                </span>
              @break
              @case('REGULARIZACIÓN DE MARCADO')
                <span>
                  DEL REGIMEN DE ASISTENCIA, Capítulo I DE LA JORNADA DE TRABAJO, Artículo 22 CONTROL DE ASISTENCIA, En el caso de error u omisión en el registro de la asistencia la o el servidor, este deberá ser justificado a través de Nota Interna dirigida a la Dirección General Ejecutiva, previamente aprobado por el jefe inmediato superior de área, la cual deberá ser presentada en el plazo máximo de 48 horas después del error u omisión (o primer día hábil). Pasado este tiempo no se considerará ningún tipo de reclamo.
                </span>
              @break
              @case('MAMOGRAFÍA/PAPANICOLAOU')
                <span>
                  DEL REGIMEN DE ASISTENCIA, Capítulo III DE LAS LICENCIAS, Artículo 33 LICENCIA CON GOCE DE HABER, Inciso j) Las servidoras públicas gozarán de un día de libre para exámenes de Mamografía y Papanicolaou.
                </span>
              @break
              @case('EXAMEN DE PRÓSTATA')
                <span>
                  la LEY N° 798 de 26 de abril de 2016, Artículo I OBJETO, inciso II) Los servidores públicos y trabajadores mayores de cuarenta (40) años que desarrollan sus actividades con funciones permanentes o temporales en instituciones públicas, privadas o dependientes de cualquier tipo de empleador, gozarán de tolerancia remunerada de un día hábil al año, a objeto de someterse a un examen médico de Próstata.
                </span>
              @break
              @case('EXAMEN DE COLON')
                <span>
                  la LEY N° 798 de 26 de abril de 2016, Artículo I OBJETO, inciso III) Las servidoras y los servidores públicos y las trabajadoras y los trabajadores mayores de cuarenta (40) años que desarrollan sus actividades con funciones permanentes o temporales en instituciones públicas, privadas o dependientes de cualquier tipo de empleador, gozarán de tolerancia remunerada de un día hábil al año, a objeto de someterse a un examen médico de Colon.
                </span>
              @break
              @case('MATRIMONIO')
                <span>
                  DEL REGIMEN DE ASISTENCIA, Capítulo III DE LAS LICENCIAS, Artículo 33 LICENCIA CON GOCE DE HABER, Inciso e) Por matrimonio, gozará de tres (3) días hábiles de licencia, previa presentación de la Certificación de Inscripción expedida por el Oficial de Registro Civil que acredite la fecha de realización del Matrimonio.
                </span>
              @break
              @case('NACIMIENTO DE HIJOS')
                <span>
                  DEL REGIMEN DE ASISTENCIA, Capítulo III DE LAS LICENCIAS, Artículo 33 LICENCIA CON GOCE DE HABER, Inciso b) Por nacimiento de hijos, el padre gozará de tres (3) días hábiles de licencia, con obligación de presentar el certificado de nacimiento correspondiente.
                </span>
              @break
              @case('FALLECIMIENTO DE PADRES, CONYUGE, HERMANOS O HIJOS')
                <span>
                  DEL REGIMEN DE ASISTENCIA, Capítulo III DE LAS LICENCIAS, Artículo 33 LICENCIA CON GOCE DE HABER, Inciso a) Por fallecimiento de padres, cónyuge, hermanos o hijos gozará de tres (3) días hábiles de licencia, debiendo presentar el Certificado de Defunción hasta los 5 días de finalizada la licencia.
                </span>
              @break
              @case('FALLECIMIENTO DE SUEGROS O CUÑADOS')
                <span>
                  DEL REGIMEN DE ASISTENCIA, Capítulo III DE LAS LICENCIAS, Artículo 33 LICENCIA CON GOCE DE HABER, Inciso a) Por fallecimiento de parientes políticos, suegros y cuñados, el funcionario gozará de dos (2) días hábiles de licencia, debiendo presentar el Certificado de Defunción hasta los 5 días de finalizada la licencia.
                </span>
              @break
              @case('DOCENCIA, BECAS, CURSOS, SEMINARIOS, POSTGRADOS')
                <span>
                  DEL REGIMEN DE ASISTENCIA, Capítulo I DE LA JORNADA DE TRABAJO, Artículo 24 TOLERANCIA POR DOCENCIA, ESTUDIOS UNIVERSITARIOS, MAESTRíAS, POSTGRADOS y OTROS CURSOS AUTORIZADOS POR LA INSTITUCiÓN. Las servidoras y/o servidores públicos que acrediten ante la Unidad de Recursos Humanos, mediante la presentación de los documentos pertinentes (certificados u otros), su calidad de docente universitario, normalista o estudiante de Universidad, Institutos Superiores u otros equivalentes; previa autorización respectiva del Jefe de Área y/o Inmediato superior, tendrán una toleranéia máxima de dos horas diarias. La tolerancia otorgada para docentes y estudiantes deberá ser compensada en el trabajo con una hora diaria. Para tiempos menores se aplicara la proporción equivalente. La solicitud se realizara con el respectivo formulario de Tolerancia para docentes, estudiantes, universitarios y de post grado. Este beneficio se suspenderá en los periodos de vacaciones de las Universidades y los centros de educación superior, por abandono o inasistencia reiterada a la institución de enseñanza y cuando se compruebe que el servidor público utiliza la tolerancia en actividades ajenas a la enseñanza o estudios. La Unidad de Recursos Humanos queda facultada para solicitar o verificar en cualquier momento la información acerca de los docentes o universitarios beneficiarios de la tolerancia para los fines que correspondan. Vencido el tiempo de tolerancia diaria, se computarán los minutos de atraso, abandono o falta de acuerdo a lo establecido en el presente Reglamento. Todo estudiante universitario, para solicitar y/o renovar su horario, necesariamente deberá presentar su certificación de materias aprobadas en e! periodo lectivo anterior, certificación de inscripción en el nuevo periodo académico y materias a llevar en el nuevo periodo semestral o anual, además de los horarios y duración del mismo. Asimismo el servidor público Universitario, está obligado a vencer, como mínimo, el 70% de las materias tomadas en el semestre y/o año. El incumplimiento a este requisito, será sancionado con la suspensión del beneficio.
                </span>
              @break
            @endswitch
        </div>
        @if ($departure->description)
          <div>
            {{ $departure->description }}
          </div>
        @endif
        <div>
            Para dicho efecto solicito la licencia
            @php ($from = Carbon::parse($departure->departure))
            @php ($to = Carbon::parse($departure->return))
            @if ($from->toDateString() == $to->toDateString())
              el día
              <span class="font-bold">{{ $from->ISOFormat('LL') }}</span>
              de horas
              <span class="font-bold">{{ $from->format('H:i') }}</span>
              a horas
              <span class="font-bold">{{ $to->format('H:i') }}</span>
            @else
              @if ($departure->departure_reason->name == 'DOCENCIA, BECAS, CURSOS, SEMINARIOS, POSTGRADOS')
                desde el día
                <span class="font-bold">{{ $from->ISOFormat('LL') }}</span>
                hasta el día
                <span class="font-bold">{{ $to->ISOFormat('LL') }}</span>
                de horas
                <span class="font-bold">{{ $from->format('H:i') }}</span>
                a horas
                <span class="font-bold">{{ $to->format('H:i') }}</span>
              @else
                desde el día
                <span class="font-bold">{{ $from->ISOFormat('LL') }} {{ $from->format('H:i') }}</span>
                al día
                <span class="font-bold">{{ $to->ISOFormat('LL') }} {{ $to->format('H:i') }}</span>
              @endif
            @endif
            .
        </div>
      </div>
      <div class="py-15">
        Sin otro particular, saludo a Ud. con las consideraciones más distinguidas.
      </div>
      <div class="py-15">
        Atentamente,
      </div>
      <div class="text-center m-t-75">
        @php($employee = $departure->employee)
        <div>
          <hr width="{{ strlen($employee->fullName())*10 }}">
        </div>
        <div>
          {{ $employee->fullName() }}
        </div>
        <div>
          @if ($employee->consultant())
            {{ $employee->last_consultant_contract()->position->name }}
          @else
            {{ $employee->last_contract()->position->name }}
          @endif
        </div>
        <div>
          C.I.: {{ Util::ciExt($employee) }}
        </div>
      </div>
    </div>
  </body>
</html>