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
    <div class="head center"> CONTRATO MODIFICATORIO AL CONTRATO EVENTUAL DE PRESTACIÓN DE </div>
    <div class="head center"> SERVICIOS Nº {{ $before_last_contract->contract_number }} </div>
    <div class="head center"> {{$contract->contract_number}} </div>
    <div class="content">
        <p class="text" style="text-indent: 0">
        Conste por el presente documento, un contrato  modificatorio que se suscribe al tenor de las siguientes cláusulas:
        </p>
        <p>
            <span class="title-text">CLÁUSULA PRIMERA (PARTES). -</span> 
        </p>
        <p>
        Las partes del presente Contrato Modificatorio son:
        </p>
        <p>
            <ol>
                <li>
                    <span class="title-text">La MUTUAL DE SERVICIOS AL POLICÍA, (MUSERPOL), </span><span> con domicilio en la Avenida 6 de Agosto No. 2354, de la ciudad de La Paz, representada legalmente por el </span><span class="title-text">CNL. MSc. CAD. LUCIO ENRIQUE RENÉ JIMÉNEZ VARGAS</span><span> con </span><span class="title-text">C.I. No 3475563</span><span> en su calidad de <span class="title-text">DIRECTOR GENERAL EJECUTIVO,</span><span> designado mediante Resolución Suprema Nº {{ $company->directors_designation_number }} de fecha {{ Carbon::parse($company->directors_designation_date)->ISOFormat('LL') }}.</span>
                </li>
                <li>
                    <span class="title-text">{{ Util::fullName($contract->employee) }}</span><span> con</span><span class="title-text"> C.I. N°  {{ Util::ciExt($contract->employee) }}.</span><span>, mayor de edad y hábil por derecho, con domicilio en la ciudad de La Paz, que en lo sucesivo se denominará  “CONTRATADO”, quienes celebran y suscriben el presente Contrato Modificatorio, de acuerdo a los términos y condiciones siguientes:</span>
                </li>
            </ol>
        </p>
        <p>
            <span class="title-text">CLÁUSULA SEGUNDA.- (ANTECEDENTES)</span> 
        </p>
        <p>
            Que, el artículo 46 de la Constitución Política del Estado, dispone que: I.  Toda persona tiene derecho: 1. Al trabajo digno, con seguridad industrial, higiene y salud ocupacional, sin discriminación y con remuneración  o salario justo, equitativo y satisfactorio, que le asegure para si y su familia una existencia digna. II. El Estado protegerá el ejercicio del trabajo en todas sus formas. III. Se prohíbe toda forma  de trabajo  forzoso u otro modo análogo de explotación que obligue a una persona a realizar labores sin su consentimiento.
        </p>
        <p>
            Que Artículo 123 de la Constitución Política del Estado dispone: La Ley sólo dispone para lo venidero y no tendrá efecto retroactivo, excepto en materia laboral, cuando lo determine expresamente a favor de las trabajadoras y de los trabajadores; en materia penal, cuando beneficie a la imputada o al imputado; en materia de corrupción, para investigar, procesar y sancionar los delitos cometidos por servidores públicos contra los intereses del Estado; y en el resto de los casos señalados por la Constitución. 
        </p>
        <p>
            Mediante Decreto Supremo N° 1446 de fecha 19 de diciembre de 2012, se creó  la Mutual de Servicios al Policía – MUSERPOL, Institución Pública Descentralizada de duración indefinida y patrimonio  propio, con autonomía de gestión administrativa, financiera, legal y técnica, bajo tuición del Ministerio de Gobierno.
        </p>
        <p>
            En fecha {{ Carbon::parse($before_last_contract->start_date)->isoFormat('LL') }}, la Mutual de Servicios al Policía – MUSERPOL, suscribió el Contrato Eventual de Prestación de Servicios<span class="title-text"> Nº {{ $before_last_contract->contract_number }}</span> en adelante Contrato Principal con <span class="title-text">{{ Util::fullName($before_last_contract->employee) }}</span> para que preste Servicios de <span class="title-text">{{ $before_last_contract->position->name }} </span>por un plazo computable a partir  del {{ Carbon::parse($before_last_contract->start_date)->isoFormat('LL') }} hasta el {{ Carbon::parse($before_last_contract->end_date)->isoFormat('LL') }}.
        </p>
        <p>
            En atención a Instructivo  DIR.GRAL.EJEC./D.A.A./021/2022, Informe MUSERPOL/DAA/URRHH N° 061/2022  y nota MUSERPOL/DAA/RRHH. N°176/2022 de 30 de junio de 2022 a efectos de dar continuidad a las actividades regulares de la institución, se solicita efectuar la elaboración  de adenda a los contratos suscritos con el personal eventual.  
        </p>
        <p>
            Los referidos antecedentes, se radicaron en la Dirección de Asesoramiento Jurídico Administrativo y Defensa Institucional de la MUSERPOL en fecha 30 de junio de 2022 para proceder  según normativa vigente.
        </p>
        <p>
            <span class="title-text">CLÁUSULA TERCERA.- (OBJETO DEL CONTRATO MODIFICATORIO)</span> 
        </p>
        <p>
            <span>El objeto del presente Contrato Modificatorio es la Modificación de la Cláusula Tercera del Contrato Principal.</span>
        </p>
        <p>
            <span class="title-text">CLÁUSULA CUARTA.- (MODIFICACIÓN AL CONTRATO) </span> 
        </p>
        <p>
            En consideración a los antecedentes descritos, el plazo  establecido en la Cláusula Tercera en el Contrato Principal se amplía  hasta el {{ Carbon::parse($contract->end_date)->isoFormat('LL') }}, mismo  que se computará a partir  del día siguiente de finalización del contrato principal.
        </p>
        <p>
            <span class="title-text">CLÁUSULA QUINTA.- (VIGENCIA DE LAS CLÁUSULAS DEL CONTRATO PRINCIPAL) </span> 
        </p>
        <p>
            Salvo las modificaciones contempladas expresamente en el presente Contrato Modificatorio, se declaran válidas y firmes las demás Cláusulas contenidas en el Contrato Principal.
        </p>
        <p>
            <span class="title-text">CLÁUSULA SEXTA.- (CONFORMIDAD Y ACEPTACIÓN) </span> 
        </p>
        <p>
        En señal de conformidad y para su fiel y estricto cumplimiento, firman las partes el presente Contrato en cuatro ejemplares de un mismo tenor y validez.
        </p>
        <p>        
            La Paz, {{ Carbon::parse($contract->start_date)->subDays(1)->isoFormat('LL') }}
        </p>

        <p class="firma center title-text">
            {{ Util::fullName($contract->employee) }}<br>
            C.I. N° {{ Util::ciExt($contract->employee) }}
        </p>
    </div>
</div>
</body>
</html>
