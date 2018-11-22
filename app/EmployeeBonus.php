<?php

namespace App;

use App\Helpers\Util;
use Carbon;
use \Milon\Barcode\DNS2D;

class EmployeeBonus
{
  function __construct($contracts, $year)
  {
    $employee = $contracts[0]->employee;
    $contract = $employee->last_contract();

    $this->code_image = null;

		// Common data
    $this->employee_id = $employee->id;
    $this->year = $year;
    $this->nua_cua = $employee->nua_cua;
    $this->ci = $employee->identity_card;
    $this->id_ext = $employee->city_identity_card->shortened;
    $this->insurance_company_id = $contract->insurance_company_id;
    $this->ci_ext = $this->ci . ' ' . $this->id_ext;
    $this->first_name = $employee->first_name;
    $this->second_name = $employee->second_name;
    $this->last_name = $employee->last_name;
    $this->mothers_last_name = $employee->mothers_last_name;
    $this->full_name = implode(" ", [$this->last_name, $this->mothers_last_name, $this->first_name, $this->second_name]);
    $this->account_number = $employee->account_number;
    $this->birth_date = Carbon::parse($employee->birth_date)->format('d/m/Y');
    $this->gender = $employee->gender;
    $this->charge = $contract->position->charge->name;
    $this->position = $contract->position->name;
    $this->start_date = null;
    $this->end_date = null;
    $this->management_entity = $employee->management_entity->name;
    $this->management_entity_id = $employee->management_entity->id;
    $this->worked_days = (object)[
      'months' => 0,
      'days' => 0,
    ];
    $this->ovt = (object)[
      'insurance_company_id' => $contract->insurance_company->ovt_id,
      'management_entity_id' => $employee->management_entity->ovt_id,
      'contract_mode' => $employee->last_contract()->contract_mode->ovt_id,
      'contract_type' => $employee->last_contract()->contract_type->ovt_id,
    ];

    // Extra data
    $this->position_group = $contract->position->position_group->name;
    $this->position_group_id = $contract->position->position_group->id;
    $this->valid = false;
    $this->calc_worked_moths($contracts, $year);
  }

  private function set_valid()
  {
    $this->valid = ($this->worked_days->months >= 3) ? true : false;
  }

  private function calc_worked_moths($contracts, $year)
  {
    $results = [];
    foreach ($contracts as $i => $contract) {
      $results[] = $this->calc_months_days($contract, $year);
    }

    $result = (object)[
      'start' => null,
      'end' => null,
      'months' => 0,
      'days' => 0
    ];

    $len = count($results);

    foreach ($results as $i => $contract) {
      if ($contract->months >= 3) {
        if (!$result->start) {
          $result->start = $contract->start->format('d/m/Y');
        }
        $result->end = $contract->end->format('d/m/Y');
        $result->months += $contract->months;
        $result->days += $contract->days;
      }
      if (prev($results)) {
        if ($results[$i - 1]->months < 3 && $results[$i - 1]->end->add(1, 'day') == $contract->start) {
          $result->start = $results[$i - 1]->start->format('d/m/Y');
          $result->months += $contract->months;
          $result->days += $contract->days;
          if ($contract->months < 3) {
            $result->end = $contract->end->format('d/m/Y');
            $result->months += $contract->months;
            $result->days += $contract->days;
          }
        }
      }
    }
    while ($result->days >= 30) {
      $result->days -= 30;
      $result->months++;
    }

    $this->start_date = $result->start;
    $this->end_date = $result->end;

    $this->worked_days = (object)[
      'months' => $result->months,
      'days' => $result->days,
    ];

    $this->set_valid();
  }

  private function calc_months_days($contract, $year)
  {
    $start = Carbon::parse($contract->start_date);
    if ($start->year < $year) {
      $start = Carbon::create($year, 1, 1);
    }
    if (!$contract->retirement_date && !$contract->end_date) {
      $end = Carbon::create($year, 12, 1)->endOfMonth()->startOfDay();
    } else {
      $end = $contract->retirement_date ? Carbon::parse($contract->retirement_date) : Carbon::parse($contract->end_date);
    }

    $months = $end->month - $start->month - 1;
    $last_day_of_month = Carbon::parse($end->format('Y-m-d'));
    $last_day_of_month = $last_day_of_month->endOfMonth()->startOfDay()->day;

    if ($last_day_of_month != 30 && $end->day == $last_day_of_month) {
      $days = (30 - $start->day + 1) + 30;
    } else {
      $days = (30 - $start->day + 1) + ($end->day);
    }
    while ($days >= 30) {
      $days -= 30;
      $months++;
    }

    return (object)[
      'start' => $start,
      'end' => $end,
      'months' => $months,
      'days' => $days
    ];
  }
}
