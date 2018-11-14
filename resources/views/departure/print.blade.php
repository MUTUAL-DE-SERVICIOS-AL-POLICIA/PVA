
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aviso de afiliacion y reingreso del trabajador</title>
    <style>
        html,
        body {
            font-size: 12pt;
            font-family: Arial;
        }
        
       /* 
        .dotted {
            border-bottom: 1px dotted;
        }
        .b-top {
            border-top: 1px solid;   
        }
        .no-border {
            border: 0;
        }
        
        .firma {
            width: 400px;
            height: 200px;
            display: table-cell;
            vertical-align: bottom;
        }*/

        .box-b {
            border: 1px solid;
        }
        .box-bt {
            border-top: 1px solid;
        }
        .box-bb {
            border-bottom: 1px solid;
        }
        .box-bl {
            border-left: 1px solid;
        }
        .box-br {
            border-right: 1px solid;
        }
        .box-r {
            border-radius: 10px;
        }
        .box-rtl {
            border-top-left-radius: 10px;
        }
        .box-rtr {
            border-top-right-radius: 10px;
        }
        .box-rbl {
            border-bottom-left-radius: 10px;
        }
        .box-rbr {
            border-bottom-right-radius: 10px;
        }
        .box-primary {
            padding: 10px;
            min-height: 530px;
            margin: 10px 0 10px 0;
        }
        .b-silver {
            background: #EFEFEF;
            border-radius: : 10px;
        }
        .col {
            float: left;
            padding: 0 0 0 15px; 
        }
        .clear {
            clear: both;
        }
        .title {
            font-weight: bold;
            font-size: 10pt;
            text-align: center;
        }
        .center{
            text-align: center;
        }
        .min-size {
            font-weight: 7pt;
        }
    </style>
</head>
<body> 
    <div class="box-b box-primary box-r">
        <div style="border-bottom: 1px solid">
            <div class="col" style="width: 20%">
                <img src="{{ public_path("/img/logo.png") }}" width="200px">
            </div>
            <div class="col center" style="width: 55%">
                <br>
                <span class="title">MUTUAL DE SERVICIOS AL POLÍCIA "MUSERPOL"</span><br>
                <span class="title">DIRECCIÓN DE ASUNTOS ADMINISTRATIVOS</span><br>
                <span class="title">UNIDAD DE RECURSOS HUMANOS</span><br>
                <span class="title">FORMULARIO DE SOLICITUD</span><br>
            </div>
            <div class="col min-size" style="width: 20%;">
                <br>
                <div class="">
                    <div class="col box-br box-bb box-rtl b-silver" style="width: 40%;"> No.</div>
                    <div class="col box-b box-rtr" style="width: 40%;">...</div>
                    <div class="clear"></div>
                    <div class="col box-br box-bb b-silver" style="width: 40%;"> Fecha</div>
                    <div class="col box-b" style="width: 40%;">...</div>
                    <div class="clear"></div>
                    <div class="col box-br box-bb box-rbl b-silver" style="width: 40%;"> Tipo</div>
                    <div class="col box-b box-rbr" style="width: 40%;">...</div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        
    </div>
    <br>
    <hr>
    <br>
    <div class="box">
        aaa

    </div>


    {{-- <div class="primary">
        <div class="head">
            <div class="col" style="width: 35%">
                <img src="{{ public_path("/img/logo.png") }}" width="200px">
            </div>
            <div class="col center" style="width: 30%">
                <br>
                <span class="title">FORMULARIO DE SOLICITUD</span>
            </div>
            <div class="col" style="width: 35%;text-align: right;font-size: 18pt;font-weight: bold;text-transform: uppercase;">
                <span style="background: #EFEFEF"> {{ $departure->departure_reason->departure_type->name }} </span>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content">
            <br>
            <div class="col" style="width: 12%">NOMBRE: </div>
            <div class="col dotted center" style="width: 88%"> {{ implode(' ', [$departure->contract->employee->first_name, $departure->contract->employee->last_name, $departure->contract->employee->mothers_last_name]) }} </div>
            <div class="clear"></div>
            <div class="col" style="width: 12%">OFICINA: </div>
            <div class="col dotted center" style="width: 88%"> {{ $departure->contract->position->position_group->name }} </div>
            <div class="clear"></div>
            <div class="col" style="width: 12%">DESTINO: </div>
            <div class="col dotted center" style="width: 88%"> {{ $departure->destiny }} </div>
            <div class="clear"></div>
            <div class="col" style="width: 12%">MOTIVO: </div>
            <div class="col dotted center" style="width: 88%"> {{ $departure->departure_reason->name }} </div>
            <div class="clear"></div>
            <br>
            <div style="border-top: 1px solid;border-bottom: 1px solid;">
                <br>
                <div class="col" style="width: 25%">FECHA DE SALIDA: </div><div class="col dotted center" style="width: 25%"> {{ Carbon::parse($departure->departure_date)->format('d/m/Y') }} </div>
                <div class="col">&nbsp;</div>
                <div class="col" style="width: 25%">HORA DE SALIDA: </div><div class="col dotted center" style="width: 20%"> {{ Carbon::parse($departure->departure_time)->format('H:i') }} </div>
                <div class="clear"></div>
                
                <div class="col" style="width: 25%">FECHA DE RETORNO: </div><div class="col dotted center" style="width: 25%"> {{ Carbon::parse($departure->return_date)->format('d/m/Y') }} </div>
                <div class="col">&nbsp;</div>
                <div class="col" style="width: 25%">HORA DE RETORNO: </div><div class="col dotted center" style="width: 20%"> {{ Carbon::parse($departure->return_time)->format('H:i') }} </div>
                <div class="clear"></div>
                <br>
            </div>
            <br>
            <div class="col" style="width: 22%">OBSERVACIONES: </div><div class="col dotted" style="width: 78%"> {{ $departure->description }} </div>
            <div class="clear"></div>
            <div class="col">
                <p class="firma center">
                    SOLICITANTE
                </p>
            </div>
            <div class="col">
                <p class="firma center">
                    AUTORIZADO
                </p>
            </div>
            <div class="clear"></div>
        </div>        
    </div>
    <div style="font-size: 6pt" align="right"> Impreso el {{ date('d/m/Y H:i') }} </div>
    <br>
    <hr>
    <br>
    <div class="primary">
        <div class="head">
            <div class="col" style="width: 35%">
                <img src="{{ public_path("/img/logo.png") }}" width="200px">
            </div>
            <div class="col center" style="width: 30%">
                <br>
                <span class="title">FORMULARIO DE SOLICITUD</span>
            </div>
            <div class="col" style="width: 35%;text-align: right;font-size: 18pt;font-weight: bold;text-transform: uppercase;">
                <span style="background: #EFEFEF"> {{ $departure->departure_reason->departure_type->name }} </span>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content">
            <br>
            <div class="col" style="width: 12%">NOMBRE: </div>
            <div class="col dotted center" style="width: 88%"> {{ implode(' ', [$departure->contract->employee->first_name, $departure->contract->employee->last_name, $departure->contract->employee->mothers_last_name]) }} </div>
            <div class="clear"></div>
            <div class="col" style="width: 12%">OFICINA: </div>
            <div class="col dotted center" style="width: 88%"> {{ $departure->contract->position->position_group->name }} </div>
            <div class="clear"></div>
            <div class="col" style="width: 12%">DESTINO: </div>
            <div class="col dotted center" style="width: 88%"> {{ $departure->destiny }} </div>
            <div class="clear"></div>
            <div class="col" style="width: 12%">MOTIVO: </div>
            <div class="col dotted center" style="width: 88%"> {{ $departure->departure_reason->name }} </div>
            <div class="clear"></div>
            <br>
            <div style="border-top: 1px solid;border-bottom: 1px solid;">
                <br>
                <div class="col" style="width: 25%">FECHA DE SALIDA: </div><div class="col dotted center" style="width: 25%"> {{ Carbon::parse($departure->departure_date)->format('d/m/Y') }} </div>
                <div class="col">&nbsp;</div>
                <div class="col" style="width: 25%">HORA DE SALIDA: </div><div class="col dotted center" style="width: 20%"> {{ Carbon::parse($departure->departure_time)->format('H:i') }} </div>
                <div class="clear"></div>
                
                <div class="col" style="width: 25%">FECHA DE RETORNO: </div><div class="col dotted center" style="width: 25%"> {{ Carbon::parse($departure->return_date)->format('d/m/Y') }} </div>
                <div class="col">&nbsp;</div>
                <div class="col" style="width: 25%">HORA DE RETORNO: </div><div class="col dotted center" style="width: 20%"> {{ Carbon::parse($departure->return_time)->format('H:i') }} </div>
                <div class="clear"></div>
                <br>
            </div>
            <br>
            <div class="col" style="width: 22%">OBSERVACIONES: </div><div class="col dotted" style="width: 78%"> {{ $departure->description }} </div>
            <div class="clear"></div>
            <div class="col">
                <p class="firma center">
                    SOLICITANTE
                </p>
            </div>
            <div class="col">
                <p class="firma center">
                    AUTORIZADO
                </p>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div style="font-size: 6pt" align="right"> Impreso el {{ date('d/m/Y H:i') }} </div> --}}
</body>
</html>
