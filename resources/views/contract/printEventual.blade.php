<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aviso de afiliacion y reingreso del trabajador</title>
</head>
<body>
    <br>
<div class="doc">
    <div class="head center"> CONTRATO EVENTUAL DE PRESTACIÓN DE SERVICIOS Nº {{ $contract->contract_number }} </div>
    <div class="content">
        <p class="text" style="text-indent: 0">
            Conste por el presente “Contrato Eventual de Prestación de Servicios” que suscriben por una parte la <span class="title-text">MUTUAL DE SERVICIOS AL POLICIA - MUSERPOL</span>, representado legalmente por el <span class="title-text" style="text-align: justify;">Sr. Cnl. DESP. EDGAR JOSE CORTEZ ALBORNOZ con C.I. No. 3351371, en su calidad de DIRECTOR GENERAL EJECUTIVO de la MUSERPOL</span>, designado mediante Resolución Suprema Nº {{ $company->directors_designation_number }} de fecha {{ Carbon::parse($company->directors_designation_date)->ISOFormat('LL') }}, y por otra parte <span class="title-text up">{{ Util::fullName($contract->employee) }} con C.I. N° {{ Util::ciExt($contract->employee) }} </span>, mayor de edad y hábil por derecho, con domicilio en la ciudad de {{ $contract->employee->location }}, que en lo sucesivo se denominará el <span class="title-text">“CONTRATADO”</span> quienes celebran el presente contrato eventual, de acuerdo a los términos y condiciones siguientes:
        </p>
        <p>
            <span class="title-text">CLÁUSULA PRIMERA (ANTECEDENTES). -</span> En estricta aplicación del Decreto Supremo N° 1446 de fecha 19 de diciembre de 2012, se creó la Mutual de Servicios al Policía – MUSERPOL como una Institución Pública Descentralizada de duración indefinida y patrimonio propio, con autonomía de gestión administrativa, financiera, legal y técnica, bajo tuición del Ministerio de Gobierno. Mediante Decretos Supremos 2829 de 6 de julio de 2016 y 3231 de 28 de junio de 2017 se modifica el D.S. 1446 de creación de la MUSERPOL. Para la realización de los fines y alcanzar los objetivos institucionales, se requiere la contratación de personal eventual con el fin de realizar la actividades administrativas, técnicas y legales propias de la Institución.
        </p>
        @if ($contract->hiring_reference_number)
        <p>
            Mediante CITE: <span class="">{{ $contract->rrhh_cite }} </span> de fecha {{ Carbon::parse($contract->rrhh_cite_date)->day }} de {{ Carbon::parse($contract->rrhh_cite_date)->formatLocalized('%B') }} de {{ Carbon::parse($contract->rrhh_cite_date)->year }}, como resultado de la Convocatoria: {{ $contract->hiring_reference_number }} dentro del Proceso de Contratación para el cargo de: <span class="title-text">“{{ $contract->position->name }}”</span>, el Director General Ejecutivo y la Jefatura de la Unidad de Recursos Humanos de la MUSERPOL, instruyen la elaboración de Contrato del Personal Eventual de <span class="cap">{{ Util::fullName($contract->employee, 'lowercase') }}</span>.
        </p>
        @else
            @if ($contract->performance_cite)
            <p>
                Mediante CITE: <span class="">{{ $contract->rrhh_cite }} </span> de fecha {{ Carbon::parse($contract->rrhh_cite_date)->day }} de {{ Carbon::parse($contract->rrhh_cite_date)->formatLocalized('%B') }} de {{ Carbon::parse($contract->rrhh_cite_date)->year }}, como resultado de la Evaluación de Personal realizado mediante CITE: {{ $contract->performance_cite }}, el Director General Ejecutivo y la Jefatura de la Unidad de Recursos Humanos de la MUSERPOL, instruyen la elaboración de Contrato del Personal Eventual de <span class="cap">{{ Util::fullName($contract->employee, 'lowercase') }}</span>.
            </p>
            @else
            <p>
                Mediante CITE: <span class="">{{ $contract->rrhh_cite }} </span> de fecha {{ Carbon::parse($contract->rrhh_cite_date)->day }} de {{ Carbon::parse($contract->rrhh_cite_date)->formatLocalized('%B') }} de {{ Carbon::parse($contract->rrhh_cite_date)->year }}, como resultado de la evaluación efectuada por parte del inmediato superior, en coordinación con el Director General Ejecutivo y la Jefatura de la Unidad de Recursos Humanos, se determinó la elaboración del Contrato del Personal Eventual de <span class="cap">{{ Util::fullName($contract->employee, 'lowercase') }}</span>.
            </p>
            @endif
        @endif

        <p>
            <span class="title-text">CLÁUSULA SEGUNDA (OBJETO). -</span> Por los antecedentes expuestos, la <span class="title-text">MUSERPOL</span> procede a suscribir el presente contrato eventual con el <span class="title-text">CONTRATADO</span> para que desempeñe funciones como <span class="title-text up"> {{ $contract->position->name }} </span>
        </p>
        <p>
            Asimismo, se aclara que <span class="title-text">“MUSERPOL”</span>, por razones de mejor servicio, podrá destinar al <span class="title-text">CONTRATADO</span> temporal o permanentemente a otra unidad, departamento o jefatura, debiendo previamente elaborarse el contrato modificatorio correspondiente.
        </p>
        <p>
            El <span class="title-text">CONTRATADO</span> podrá ejercer Interinato, sin descuidar las funciones para las que fue contratado, previa instrucción escrita de la Máxima Autoridad Ejecutiva y de acuerdo a las necesidades de <span class="title-text">MUSERPOL</span>.
        </p>
        <p>
            <span class="title-text">CLÁUSULA TERCERA (PLAZO).</span> El plazo del presente contrato será a partir del {{ Carbon::parse($contract->start_date)->day }} de {{ Carbon::parse($contract->start_date)->formatLocalized('%B') }} del {{ Carbon::parse($contract->start_date)->year }} hasta el {{ Carbon::parse($contract->end_date)->day }} de {{ Carbon::parse($contract->end_date)->formatLocalized('%B') }} del {{ Carbon::parse($contract->end_date)->year }}, por tratarse de un contrato eventual, queda sobreentendido que el mismo quedará fenecido en la fecha señalada en la presente cláusula, salvando la previsión contenida en la Cláusula Décima del presente contrato.
        </p>
        @php($schedule = $contract->job_schedules[0])
        @php($turno1 = str_pad($schedule->start_hour,2,0,STR_PAD_LEFT).':'.str_pad($schedule->start_minutes,2,0,STR_PAD_LEFT).' a '.str_pad($schedule->end_hour,2,0,STR_PAD_LEFT).':'.str_pad($schedule->end_minutes,2,0,STR_PAD_LEFT))
        @if(isset($contract->job_schedules[1]))
        @php($schedule2 = $contract->job_schedules[1])
        @php($turno2 = ' y de '.str_pad($schedule2->start_hour,2,0,STR_PAD_LEFT).':'.str_pad($schedule2->start_minutes,2,0,STR_PAD_LEFT).' a '.str_pad($schedule2->end_hour,2,0,STR_PAD_LEFT).':'.str_pad($schedule2->end_minutes,2,0,STR_PAD_LEFT))
        @else
        @php($turno2 = '')
        @endif
        <p>
            <span class="title-text">CLÁUSULA CUARTA (JORNADA LABORAL). -</span> El horario de cumplimiento de funciones por parte del <span class="title-text">CONTRATADO</span> es de {{ $turno1 }}{{ $turno2 }}; asimismo la Entidad en caso de necesidad, establecerá horarios específicos diferentes para fines institucionales.
        </p>
        <p>
            <span class="title-text">CLÁUSULA QUINTA (VACACIÓN). -</span> Por tratarse de un contrato eventual, el <span class="title-text">CONTRATADO</span> no tiene derecho a vacación.
        </p>
        <p>
            <span class="title-text">CLÁUSULA SEXTA (REMUNERACION). -</span> El <span class="title-text">CONTRATADO</span> percibirá el sueldo mensual de <span class="title-text">Bs.- {{ Util::format_number($contract->position->charge->base_wage) }}.- ( {{ Util::convertir($contract->position->charge->base_wage) }} Bolivianos)</span> sueldo que será pagado y ejecutado a la Partida Presupuestaria 12100 (Personal Eventual).
        </p>
        <p>
            Asimismo, de la remuneración acordada la <span class="title-text">MUSERPOL</span> hará las retenciones impositivas aplicables si correspondiera y los descuentos determinados por Ley. La forma de pago será la establecida por <span class="title-text">MUSERPOL</span> pudiendo el pago efectuarse por la Unidad de Tesorería o a través de una entidad bancaria.
        </p>
        <p>
            <span class="title-text">CLÁUSULA SÉPTIMA (VIAJES POR COMISIÓN). -</span> El <span class="title-text">CONTRATADO</span> podrá realizar viajes oficiales, debiendo adecuarse a la escala correspondiente para lo cual deberán asignarse los recursos para pasajes y viáticos en las partidas respectivas.
        </p>
        <p>
            <span class="title-text">CLÁUSULA OCTAVA (PROPIEDAD DE ACTIVOS). -</span> Los activos de propiedad de la <span class="title-text">MUSERPOL</span> que se entregarán al <span class="title-text">CONTRATADO</span> tienen la calidad de herramientas de trabajo y en ningún caso se considerarán de su propiedad, por lo que á momento de la desvinculación contractual o requerimiento escrito de la <span class="title-text">MUSERPOL</span> deberá devolverlos en la misma forma que los recibió mediante Acta, Solvencia o Documento de la unidad correspondiente.
        </p>
        <p>
            Se deja establecido que los resultados del trabajo que obtenga y/o desarrolle el <span class="title-text">CONTRATADO</span> emergente del presente contrato, serán de propiedad, uso y disposición exclusiva de la <span class="title-text">MUSERPOL</span>.
        </p>
        <p>
            <span class="title-text">CLÁUSULA NOVENA (DE LAS OBLIGACIONES DE LAS PARTES). -</span>
        </p>
        <p>
            La <span class="title-text">MUSERPOL</span> se obliga a:
            <ol>
                <li>Cumplir con la Constitución Política del Estado y Leyes del Estado Plurinacional de Bolivia.</li>
                <li>Efectuar el pago oportuno de su sueldo al <span class="title-text">CONTRATADO</span> de acuerdo a las condiciones establecidas en el presente contrato.</li>
                <li>Proporcionar los medios materiales para que el <span class="title-text">CONTRATADO</span> pueda desarrollar sus actividades.</li>
                <li>Promover la capacitación del <span class="title-text">CONTRATADO</span> a efectos de procurar la eficiencia en el desempeño de sus funciones.</li>
                <li>Respetar y cumplir lo establecido en la Ley 1178 y otras disposiciones conexas.</li>
                <li>Mantener y precautelar un trato enmarcado en los principios de dignidad, respeto mutuo y ética funcionaria.</li>
            </ol>
        </p>
        <p>
            El <span class="title-text">CONTRATADO</span> tiene las siguientes obligaciones generales:
            <ol>
                <li>Cumplir con la Constitución Política del Estado y Leyes del Estado Plurinacional de Bolivia.</li>
                <li>Desarrollar su trabajo cumpliendo el horario laboral establecido por la <span class="title-text">MUSERPOL</span>.</li>
                <li>Cumplir las funciones inherentes al puesto asignado.</li>
                <li>Prestar colaboración a sus superiores e inferiores en todo momento durante la jornada laboral.</li>
                <li>Conocer los objetivos, normas y políticas de la <span class="title-text">MUSERPOL</span>.</li>
                <li>No revelar a entidad o persona alguna, ningún dato o información concerniente a las actividades propias de la <span class="title-text">MUSERPOL</span> que impliquen infidencia ni aun después de concluido este contrato.</li>
                <li>Guardar el debido respeto con los afiliados del sector activo y sector pasivo, atendiendo sus requerimientos con amabilidad y diligencia, así como para con sus compañeros de trabajo, dependientes o superiores.</li>
                <li>Conservar conducta ética y moral intachable.</li>
                <li>Cumplir con los Reglamentos Internos de la institución.</li>
                <li>No incurrir con acciones disciplinarias (faltas, llamadas de atención y abandonos).</li>
                <li>Cuidar los activos fijos asignados evitando daños materiales a los bienes de la <span class="title-text">MUSERPOL</span>.</li>
                <li>La obligación de presentar su informe final de actividades en caso de renuncia y/o culminación de contrato.</li>
                <li>Obrar diligentemente en su labor, porque en caso de contravenciones al Ordenamiento Jurídico y las Normas Específicas vigentes, se hará pasible a responsabilidades establecidas en la normativa vigente.</li>
            </ol>
        </p>
        <p>
            <span class="title-text">CLÁUSULA DÉCIMA (RESOLUCIÓN). -</span> El presente contrato, podrá ser resuelto antes del término estipulado en la Cláusula Tercera del presente contrato, sin necesidad de preaviso o requerimiento judicial o extrajudicial por las siguientes causales:
        </p>
        <p>
            <ol>
                <li>Incumplimiento total o parcial del presente contrato.</li>
                <li>Incumplimiento de una de las cláusulas del presente contrato.</li>
                <li>Por determinación unilateral de la Entidad.</li>
                <li>Perjuicio material causado por el <span class="title-text">CONTRATADO</span> con intensión en los instrumentos de trabajo.</li>
                <li>Inasistencia injustificada del <span class="title-text">CONTRATADO</span> por más de tres (3) días continuos y seis (6) días discontinuos.</li>
                <li>Incumplimiento total o parcial del Reglamento Interno de la <span class="title-text">MUSERPOL</span> y la normativa interna, previo el proceso administrativo correspondiente.</li>
                <li>Por renuncia del <span class="title-text">CONTRATADO</span> mediante justificación fundamentada y notificada por escrito a la <span class="title-text">MUSERPOL</span>, por lo menos con anticipación de quince (15) días calendario. En caso de requerir un tiempo menor al establecido, el <span class="title-text">CONTRATADO</span> deberá presentar, adjunto a su renuncia, los sustentos necesarios, consistentes y oportunos que originen la misma, debiendo aguardar el <span class="title-text">CONTRATADO</span> la aceptación de la renuncia. En ambas cuestiones, en caso que el contratado renuncie antes del término y/o vencimiento de la fecha establecida en la cláusula tercera, el contratado deberá sufragar los gastos de publicación en los que se incurre para una nueva convocatoria al mismo cargo y/o puesto que desempeñaba en la institución.</li>
            </ol>
        </p>
        <p>
            En todos los casos, terminado el plazo requerido y previa presentación del certificado de devolución de activos fijos, informe final de actividades, inventario de la documentación a su cargo, y formulario de solvencia con el sello de todas las Unidades Funcionales de <span class="title-text">MUSERPOL</span>, la Entidad pagará los haberes que correspondan por los servicios prestados hasta la fecha efectiva de la resolución del contrato.
        </p>
        <p>
            <span class="title-text">CLÁUSULA DÉCIMA PRIMERA (CONFORMIDAD). -</span> Las partes manifiestan su conformidad con cada una de las cláusulas estipuladas en el presente contrato y se comprometen a su fiel y estricto cumplimiento, firmando al pie en tres ejemplares con un sólo tenor y un mismo efecto.
        </p>
        <p class="center">
            La Paz, {{ Carbon::parse($contract->start_date)->day }} de {{ Carbon::parse($contract->start_date)->formatLocalized('%B') }} del {{ Carbon::parse($contract->start_date)->year }}
        </p>

        <p class="firma center title-text">
            {{ Util::fullName($contract->employee) }}<br>
            C.I. N° {{ Util::ciExt($contract->employee) }}
        </p>
    </div>
</div>
</body>
</html>
