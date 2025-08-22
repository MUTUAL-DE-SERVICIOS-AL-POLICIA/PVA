<?php

use App\Helpers\Util;
use App\Http\Controllers\Api\V1\PayrollController as Payroll;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title->name }} {{ $title->year }}</title>
</head>

<body>
    <div>
        <div class="header-left">
            <img id="header-image" src="{{ public_path() . '/img/logo.png' }}">
        </div>
        <div class="header-center">
            <h2>
                {{ implode(' - ', array_filter([$title->name, $title->subtitle, $title->management_entity, $title->position_group, $title->employer_number])) }}
            </h2>
            <h3>{{ $title->table_header }} - {{ $title->year }}</h3>
            <h3>(EXPRESADO EN BOLIVIANOS)</h3>
        </div>
        <div class="header-right">
            <span style="font-weight: bold; margin-bottom: 2px;">{{ $title->report_name }}</span>
            <p>NIT {{ $company->tax_number }}</p>
        </div>
    </div>

    <table align="center">
        <thead>
            <tr>
                @php($table_header_space1 = 13)
                @php($table_header_space2 = 4)
                <th colspan="{{ $table_header_space1 }}"
                    style="border-left: 1px solid white; border-top: 1px solid white; background-color: white;"></th>
                <th colspan="{{ $table_header_space2 }}">{{ $title->table_header }}</th>
            </tr>
            <tr>
                <th width="1%">N°</th>
                <th width="2%">C.I.</th>
                <th width="10%">TRABAJADOR</th>
                @if (!$title->management_entity)
                    <th width="1%">CUENTA BANCO UNIÓN</th>
                @endif
                <th width="1%">SEXO</th>
                <th width="1%">CARGO</th>
                <th width="3%">PUESTO</th>
                @if (!$title->position_group)
                    <th width="3%">AREA</th>
                @endif
                <th width="4%">FECHA DE INGRESO</th>
                @if (!$title->management_entity)
                    <th width="1%">FECHA VENCIMIENTO CONTRATO</th>
                @endif
                <th width="1%">DIAS TRABAJADOS</th>
                <th width="2%">HABER BÁSICO</th>
                <th width="2%">HABER BÁSICO CON INCREMENTO</th>
                <th>ENERO</th>
                <th>FEBRERO</th>
                <th>MARZO</th>
                <th>ABRIL</th>
                <?php
                $year = $title->year;
                $id_month = \App\Month::where('name', $title->month)->first()->id;
                $month = str_pad($id_month, 2, '0', STR_PAD_LEFT);
                $date = Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-02");
                $one_employee = reset($employees);
                ?>
                @if ($date >= $one_employee->admission_date_min)
                    <th width="2%">BONO ANTIGÜEDAD</th>
                    <th width="2%">INCREMENTO POR BONO ANTIGÜEDAD</th>
                    <th>ENERO</th>
                    <th>FEBRERO</th>
                    <th>MARZO</th>
                    <th>ABRIL</th>
                @endif
                <th width="2%">TOTAL GANADO</th>
                <th width="1%">Renta vejez {{ $procedure->employee_discount->elderly * 100 }}%</th>
                <th width="1%">Riesgo común {{ $procedure->employee_discount->common_risk * 100 }}%</th>
                <th width="1%">Comisión {{ $procedure->employee_discount->comission * 100 }}%</th>
                <th width="1%">Aporte solidario del asegurado
                    {{ $procedure->employee_discount->solidary * 100 }}%</th>
                <th width="1%">Aporte Nacional solidario 1%, 5%, 10%</th>
                <th width="1%">TOTAL DESCUENTOS DE LEY</th>
                <th width="3%">SUELDO NETO</th>
                <th width="3%">RC IVA {{ $procedure->employee_discount->rc_iva * 100 }}%</th>
                <th width="3%">Desc Atrasos, Faltas y Licencia S/G Haberes</th>
                <th width="1%">TOTAL DESCUENTOS</th>
                <th width="3%">LIQUIDO PAGABLE</th>
            </tr>
        </thead>
        <tbody>
            @php($total_idf = 0)
            @php($total_iva_110 = 0)
            @php($total_actualizacion = 0)
            @php($index = 1)
            @php($index2 = 0)

            @foreach ($employees as $i => $employee)
                <tr>
                    @if (count($employees) > 1)
                        @if ($i > 0 && $employee->employee_id != $employees[$i - 1]->employee_id)
                            @php(++$index)
                        @endif
                    @else
                        @php($i = 0)
                    @endif
                    <td>{{ ++$i }}</td>
                    <td>{{ $employee->ci_ext }}</td>
                    <td class="name">{{ $employee->full_name }}</td>
                    @if (!$title->management_entity)
                        <td>{{ $employee->account_number }}</td>
                    @endif
                    <td>{{ $employee->gender }}</td>
                    <td>{{ $employee->charge }}</td>
                    <td>{{ $employee->position }}</td>
                    @if (!$title->position_group)
                        <td>{{ $employee->position_group }}</td>
                    @endif
                    <td>{{ $employee->start_date }}</td>
                    @if (!$title->management_entity)
                        <td>{{ $employee->end_date }}</td>
                    @endif
                    <td>{{ $employee->worked_days }}</td>
                    <td>{{ Util::format_number($employee->previous_base_wage) }}</td>
                    <td>{{ Util::format_number($employee->base_wage) }}</td>
                    <td>{{ Util::format_number($employee->monthly_reimbursement) }}</td>
                    <td>{{ Util::format_number($employee->monthly_reimbursement) }}</td>
                    <td>{{ Util::format_number($employee->monthly_reimbursement) }}</td>
                    <td>{{ Util::format_number($employee->monthly_reimbursement) }}</td>
                    <?php
                    $year = $title->year;
                    $id_month = \App\Month::where('name', $title->month)->first()->id;
                    $month = str_pad($id_month, 2, '0', STR_PAD_LEFT);
                    $date = Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-02");
                    $one_employee = reset($employees);
                    ?>

                    @if ($date >= $one_employee->admission_date_min)
                        <td>{{ Util::format_number($employee->previous_seniority_bonus) }}</td>
                        <td>{{ Util::format_number($employee->seniority_bonus) }}</td>
                        <td>{{ Util::format_number($employee->seniority_bonus_reimbursement) }}</td>
                        <td>{{ Util::format_number($employee->seniority_bonus_reimbursement) }}</td>
                        <td>{{ Util::format_number($employee->seniority_bonus_reimbursement) }}</td>
                        <td>{{ Util::format_number($employee->seniority_bonus_reimbursement) }}</td>
                    @endif
                    
                    <td>{{ Util::format_number($employee->quotable) }}</td>
                    <td>{{ Util::format_number($employee->discount_old) }}</td>
                    <td>{{ Util::format_number($employee->discount_common_risk) }}</td>
                    <td>{{ Util::format_number($employee->discount_commission) }}</td>
                    <td>{{ Util::format_number($employee->discount_solidary) }}</td>
                    <td>{{ Util::format_number($employee->discount_national) }}</td>
                    <td>{{ Util::format_number($employee->total_amount_discount_law) }}</td>
                    <td>{{ Util::format_number($employee->net_salary) }}</td>
                    <td>{{ Util::format_number($employee->discount_rc_iva) }}</td>
                    <td>{{ Util::format_number($employee->discount_faults) }}</td>
                    <td>{{ Util::format_number($employee->total_discounts) }}</td>
                    <td>{{ Util::format_number($employee->payable_liquid) }}</td>
                </tr>
            @endforeach
            <tr class="total" style="height: 45px;">
                @if ($title->position_group)
                    @php($table_footer_space1 = 11)
                @elseif ($title->management_entity)
                    @php($table_footer_space1 = 10)
                @else
                    @php($table_footer_space1 = 11)
                @endif
                <td class="footer" colspan="{{ $table_footer_space1 }}">TOTAL PLANILLA
                    ({{ count($employees) == 0 ? 0 : $index }}
                    {{ count($employees) == 0 ? 'FUNCIONARIOS' : ($index == 1 ? 'FUNCIONARIO' : 'FUNCIONARIOS') }})
                </td>
                <td class="footer">-</td>
                <td class="footer">{{ Util::format_number($total_discounts->base_wage) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->monthly_reimbursement) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->monthly_reimbursement) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->monthly_reimbursement) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->monthly_reimbursement) }}</td>
                <td class="footer">-</td>
                <td class="footer">{{ Util::format_number($total_discounts->seniority_bonus) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->seniority_bonus_reimbursement) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->seniority_bonus_reimbursement) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->seniority_bonus_reimbursement) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->seniority_bonus_reimbursement) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->quotable) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->discount_old) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->discount_common_risk) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->discount_commission) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->discount_solidary) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->discount_national) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->total_amount_discount_law) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->net_salary) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->discount_rc_iva) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->total_faults) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->total_discounts) }}</td>
                <td class="footer">{{ Util::format_number($total_discounts->payable_liquid) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
