<?php
use \App\Helpers\Util;
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        <meta content="utf-8" http-equiv="encoding">
        <meta name="viewport" content="initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title->name }} {{ $title->year }}</title>
        <style>
            <?php include public_path('css/payroll-print.min.css') ?>
        </style>
    </head>

    <body>
        @php ($types = ['GENERAL', 'BANCO'])
        @foreach ($types as $type)
        @if ($type == 'GENERAL')
        <div style="page-break-inside: avoid; page-break-after: always;">
        @else
        <div>
        @endif

            <div class="header-left">
                <p>{{ $company->name }}</p>
                <p>NIT {{ $company->tax_number }}</p>
                <p>{{ $company->address }}</p>
            </div>

            <div class="header-right">
                <span>{{ $type }}</span>
            </div>

            <div class="header-center">
                <h2>
                    {{ $title->name }}
                </h2>
                <h3>PERSONAL EVENTUAL - {{ $title->subtitle }} {{ $title->year }}</h3>
                @if ($split_percentage)
                    <h3>CÁLCULO SOBRE EL 100% (EXPRESADO EN BOLIVIANOS)</h3>
                @else
                    <h3>(EXPRESADO EN BOLIVIANOS)</h3>
                @endif
            </div>

            <div class="header-left">
                <img id="header-image" src="{{ public_path().'/img/logo.png'}}">
            </div>

            <table align="center">
                <thead>
                    <tr>
                        <th colspan="9" style="border-left: 1px solid white; border-top: 1px solid white; background-color: white;"></th>
                        <th colspan="2">FECHAS</th>
                        <th colspan="2">TOTAL TRABAJADO</th>
                        <th colspan="3">TOTALES GANADOS</th>
                        <th colspan="1" style="border-left: 1px solid white; border-top: 1px solid white; background-color: white;"></th>
                        <th colspan="2">CÁLCULO DUODÉCIMAS</th>
                    </tr>
                    <tr>
                        <th width="1%">N°</th>
                        <th width="1%">C.I.</th>
                        <th width="7%">TRABAJADOR</th>
                        <th width="1%">CUENTA BANCO UNIÓN</th>
                        <th width="1%">FECHA NACIMIENTO</th>
                        <th width="1%">SEXO</th>
                        <th width="1%">CARGO</th>
                        <th width="3%">PUESTO</th>
                        <th width="3%">AREA</th>
                        <th width="2%">INICIO</th>
                        <th width="2%">FINAL</th>
                        <th width="1%">MESES</th>
                        <th width="1%">DIAS</th>
                        <th width="1%">ANTE PENÚLTIMO</th>
                        <th width="1%">PENÚLTIMO</th>
                        <th width="1%">ÚLTIMO</th>
                        <th width="1%">PROMEDIO ÚLTIMOS 3 MESES</th>
                        <th width="1%">AGUINALDO EN DUODÉCIMA</th>
                        <th width="1%">MESES DUODÉCIMA</th>
                        <th width="1%">AGUINALDO A PAGAR (100%)</th>
                    </tr>
                </thead>
                <tbody>
                @php ($index = 0)
                @php ($total = 0)

                @foreach ($employees as $employee)
                    @if (($type == 'BANCO' && $employee->account_number) || $type == 'GENERAL')
                    @php ($total += round($employee->bonus, 2))
                    <tr>
                        <td>{{ ++$index }}</td>
                        <td>{{ $employee->ci_ext }}</td>
                        <td class="name">{{ $employee->full_name }}</td>
                        <td>{{ $employee->account_number }}</td>
                        <td>{{ $employee->birth_date }}</td>
                        <td>{{ $employee->gender }}</td>
                        <td>{{ $employee->charge }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>{{ $employee->position_group }}</td>
                        <td>{{ $employee->start_date }}</td>
                        <td>{{ $employee->end_date }}</td>
                        <td>{{ $employee->worked_days->months }}</td>
                        <td>{{ $employee->worked_days->days }}</td>
                    @foreach ($employee->base_wages as $base_wage)
                        <td>{{ Util::format_number($base_wage) }}</td>
                    @endforeach
                        <td>{{ Util::format_number($employee->average) }}</td>
                        <td>{{ Util::format_number($employee->average/12) }}</td>
                        <td>{{ Util::format_number($employee->worked_days->months + $employee->worked_days->days/30) }}</td>
                        <td>{{ Util::format_number($employee->bonus) }}</td>
                    </tr>
                    @endif
                @endforeach
                @if (count($employees) > 0)
                    <tr>
                        <td class="footer" colspan="19">TOTAL PLANILLA ({{ $index }} {{ ($index == 1) ? 'FUNCIONARIO' : 'FUNCIONARIOS'}})</td>
                        <td class="footer">{{ Util::format_number($total) }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        @endforeach

        @if ($split_percentage)
            @php ($types = ['GENERAL', 'BANCO'])
            @php ($percentage = (100-$split_percentage))
            @foreach ($types as $type)
                <div style="page-break-inside: avoid; page-break-after: always;"></div>
                <div class="header-left">
                    <p>{{ $company->name }}</p>
                    <p>NIT {{ $company->tax_number }}</p>
                    <p>{{ $company->address }}</p>
                </div>

                <div class="header-right">
                    <span>{{ $type }}</span>
                </div>

                <div class="header-center">
                    <h2>
                        {{ $title->name }}
                    </h2>
                    <h3>PERSONAL EVENTUAL - {{ $title->subtitle }} {{ $title->year }}</h3>
                    <h3>CÁLCULO SOBRE EL {{ $percentage }}% (EXPRESADO EN BOLIVIANOS)</h3>
                </div>

                <div class="header-left">
                    <img id="header-image" src="{{ public_path().'/img/logo.png'}}">
                </div>

                <table align="center">
                    <thead>
                        <tr>
                            <th colspan="9" style="border-left: 1px solid white; border-top: 1px solid white; background-color: white;"></th>
                            <th colspan="2">FECHAS</th>
                            <th colspan="2">TOTAL TRABAJADO</th>
                            <th colspan="3">TOTALES GANADOS</th>
                            <th colspan="1" style="border-left: 1px solid white; border-top: 1px solid white; background-color: white;"></th>
                            <th colspan="2">CÁLCULO DUODÉCIMAS</th>
                        </tr>
                        <tr>
                            <th width="1%">N°</th>
                            <th width="1%">C.I.</th>
                            <th width="7%">TRABAJADOR</th>
                            <th width="1%">CUENTA BANCO UNIÓN</th>
                            <th width="1%">FECHA NACIMIENTO</th>
                            <th width="1%">SEXO</th>
                            <th width="1%">CARGO</th>
                            <th width="3%">PUESTO</th>
                            <th width="3%">AREA</th>
                            <th width="2%">INICIO</th>
                            <th width="2%">FINAL</th>
                            <th width="1%">MESES</th>
                            <th width="1%">DIAS</th>
                            <th width="1%">ANTE PENÚLTIMO</th>
                            <th width="1%">PENÚLTIMO</th>
                            <th width="1%">ÚLTIMO</th>
                            <th width="1%">PROMEDIO ÚLTIMOS 3 MESES</th>
                            <th width="1%">AGUINALDO EN DUODÉCIMA</th>
                            <th width="1%">MESES DUODÉCIMA</th>
                            <th width="1%">AGUINALDO A PAGAR ({{$percentage}}%)</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php ($index = 0)
                    @php ($total = 0)

                    @foreach ($employees as $employee)
                        @if (($type == 'BANCO' && $employee->account_number) || $type == 'GENERAL')
                        @php ($total += round($employee->bonus_percentage->first_split->value, 2))
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $employee->ci_ext }}</td>
                            <td class="name">{{ $employee->full_name }}</td>
                            <td>{{ $employee->account_number }}</td>
                            <td>{{ $employee->birth_date }}</td>
                            <td>{{ $employee->gender }}</td>
                            <td>{{ $employee->charge }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->position_group }}</td>
                            <td>{{ $employee->start_date }}</td>
                            <td>{{ $employee->end_date }}</td>
                            <td>{{ $employee->worked_days->months }}</td>
                            <td>{{ $employee->worked_days->days }}</td>
                        @foreach ($employee->base_wages as $base_wage)
                            <td>{{ Util::format_number($base_wage) }}</td>
                        @endforeach
                            <td>{{ Util::format_number($employee->average) }}</td>
                            <td>{{ Util::format_number($employee->average/12) }}</td>
                            <td>{{ Util::format_number($employee->worked_days->months + $employee->worked_days->days/30) }}</td>
                            <td>{{ Util::format_number($employee->bonus_percentage->first_split->value) }}</td>
                        </tr>
                        @endif
                    @endforeach
                    @if (count($employees) > 0)
                        <tr>
                            <td class="footer" colspan="19">TOTAL PLANILLA ({{ $index }} {{ ($index == 1) ? 'FUNCIONARIO' : 'FUNCIONARIOS'}})</td>
                            <td class="footer">{{ Util::format_number($total) }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            @endforeach
        @endif
    </body>
</html>