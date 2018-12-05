<?php
use \App\Helpers\Util;
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title->name }} {{ $title->year }}</title>
        <style>
            <?php include public_path('css/payroll-print.min.css') ?>
        </style>
    </head>

    <body>
        <div class="header-left">
            <p>{{ $company->name }}</p>
            <p>NIT {{ $company->tax_number }}</p>
            <p>{{ $company->address }}</p>
        </div>

        <div class="header-right">
            <span>{{ $title->report_name }}</span>
        </div>

        <div class="header-center">
            <h2>
                {{ implode(' - ', array_filter([$title->name, $title->subtitle, $title->position_group])) }}
            </h2>
            <h3>MES {{ $title->month }} DE {{ $title->year }}</h3>
            <h3>(EXPRESADO EN BOLIVIANOS)</h3>
        </div>

        <div class="header-left">
            <img id="header-image" src="{{ public_path().'/img/logo.png'}}">
        </div>

        <table align="center">
            <thead>
                <tr>
                    <th width="1%">N°</th>
                    <th width="2%">C.I.</th>
                    <th width="10%">TRABAJADOR</th>
                    <th width="1%">CUENTA BANCO UNIÓN</th>
                    <th width="1%">FECHA NACIMIENTO</th>
                    <th width="1%">SEXO</th>
                    <th width="3%">PUESTO</th>
                    @if (!$title->position_group)
                        <th width="3%">AREA</th>
                    @endif
                    <th width="4%">FECHA DE INGRESO</th>
                    <th width="1%">FECHA VENCIMIENTO CONTRATO</th>
                    <th width="1%">DIAS TRABAJADOS</th>
                    <th width="2%">HABER BÁSICO</th>
                    <th width="1%">AFP</th>
                    <th width="3%">SUELDO NETO</th>
                    <th width="3%">Desc Atrasos, Faltas y Licencia S/G Haberes</th>
                    <th width="3%">LIQUIDO PAGABLE</th>
                </tr>
            </thead>
            <tbody>
            @php ($index = 1)
            @foreach ($employees as $i => $employee)
                <tr>
                    @if (($i > 0) && ($employee->employee_id != $employees[$i-1]->employee_id))
                        @php (++$index)
                    @endif
                    <td>{{ ++$i }}</td>
                    <td>{{ $employee->ci_ext }}</td>
                    <td class="name">{{ $employee->full_name }}</td>
                    <td>{{ $employee->account_number }}</td>
                    <td>{{ $employee->birth_date }}</td>
                    <td>{{ $employee->gender }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->position_group }}</td>
                    <td>{{ $employee->start_date }}</td>
                    <td>{{ $employee->end_date }}</td>
                    <td>{{ $employee->worked_days }}</td>
                    <td>{{ Util::format_number($employee->base_wage) }}</td>
                    <td>{{ $employee->management_entity }}</td>
                    <td>{{ Util::format_number($employee->net_salary) }}</td>
                    <td>{{ Util::format_number($employee->discount_faults) }}</td>
                    <td>{{ Util::format_number($employee->payable_liquid) }}</td>
                </tr>
            @endforeach
                <tr class="total" style="height: 45px;">
                    @php ($table_footer_space1 = 11)
                    <td class="footer" colspan="{{ $table_footer_space1 }}">TOTAL PLANILLA ({{ $index }} {{ ($index == 1) ? 'CONSULTOR' : 'CONSULTORES'}})</td>
                    <td class="footer">{{ Util::format_number($total_discounts->base_wage) }}</td>
                    <td class="footer"></td>
                    <td class="footer">{{ Util::format_number($total_discounts->net_salary) }}</td>
                    <td class="footer">{{ Util::format_number($total_discounts->total_faults) }}</td>
                    <td class="footer">{{ Util::format_number($total_discounts->payable_liquid) }}</td>
                </tr>
            </tbody>
        </table>
    </body>

</html>