<?php

namespace App;

use App\Helpers\Util;
use Carbon;
use \Milon\Barcode\DNS2D;

class EmployeeConsultantPayroll
{
  function __construct($payroll)
  {
    $contract = $payroll->consultant_contract;
    $employee = $contract->employee;

    $this->code_image = null;

    // Common data
    $this->employee_id = $employee->id;
    $this->year = $payroll->consultant_procedure->year;
    $this->month_id = $payroll->consultant_procedure->month->order;
    $this->month_shortened = $payroll->consultant_procedure->month->shortened;
    $this->payroll_id = $payroll->id;
    $this->nua_cua = $employee->nua_cua;
    $this->ci = $employee->identity_card;
    $this->id_ext = $employee->city_identity_card->shortened;
    $this->ci_ext = $this->ci . ' ' . $this->id_ext;
    $this->first_name = $employee->first_name;
    $this->second_name = $employee->second_name;
    $this->last_name = $employee->last_name;
    $this->mothers_last_name = $employee->mothers_last_name;
    $this->full_name = implode(" ", [$this->last_name, $this->mothers_last_name, $this->first_name, $this->second_name]);
    $this->account_number = $employee->account_number;
    $this->birth_date = Carbon::parse($employee->birth_date)->format('d/m/Y');
    $this->gender = $employee->gender;
    $this->charge = $contract->consultant_position->charge->name;
    $this->position = $contract->consultant_position->name;
    $this->start_date = Carbon::parse($contract->start_date)->format('d/m/Y');
    $this->end_date = is_null($contract->end_date) ? 'Indefinido' : Carbon::parse($contract->end_date)->format('d/m/Y');
    $this->retirement_date = is_null($contract->retirement_date) ? null : Carbon::parse($contract->retirement_date)->format('d/m/Y');
    $this->base_wage = floatval($payroll->consultant_position->charge->base_wage);
    $this->management_entity = $employee->management_entity->name;
    $this->management_entity_id = $employee->management_entity->id;
    $this->unworked_days = $payroll->unworked_days;
    $this->worked_days = $this->worked_days($payroll);

    // Payable template
    $this->net_salary = null;
    $this->discount_faults = null;
    $this->payable_liquid = null;
    $this->employeeDiscounts($payroll);

    // Extra data
    $this->position_group = $contract->consultant_position->position_group->name;
    $this->position_group_id = $contract->consultant_position->position_group->id;
    $this->valid_contract = Util::valid_contract($payroll, null, 'consultant');
  }

  public function setZeroAccounts()
  {
    $this->base_wage = 0;
    $this->code = 0;
    $this->net_salary = 0;
    $this->discount_faults = 0;
    $this->payable_liquid = 0;
  }

  private function employeeDiscounts($payroll)
  {
    $this->id = $payroll->id;
    $this->code = $payroll->code;
    $this->net_salary = $this->base_wage * $this->worked_days / 30;
    $this->discount_faults = $payroll->faults;
    $this->payable_liquid = round(($this->net_salary - $this->discount_faults), 2);
  }

  public function mergePayroll($employe_payroll)
  {
    $this->worked_days += $employe_payroll->worked_days;
    $this->net_salary += $employe_payroll->net_salary;
    $this->discount_faults += $employe_payroll->discount_faults;
    $this->payable_liquid += $employe_payroll->payable_liquid;
    return $this;
  }

  public function generateImage()
  {
    $this->code_image = DNS2D::getBarcodePNG(($this->employee_id . ' ' . $this->full_name . ' ' . $this->consultant_position . ' ' . $this->month_id . ' ' . $this->year . ' ' . $this->base_wage . ' ' . $this->discount_faults . ' ' . $this->payable_liquid), "PDF417", 3, 33, array(1, 1, 1));
    return $this;
  }

  public function setWorkedDays($worked_days)
  {
    $this->worked_days = $worked_days;
    return $this;
  }

  public function getWorkedDays()
  {
    return $this->worked_days;
  }

  public function setCodeImage($code_image)
  {
    $this->code_image = $code_image;
  }

  public function setValidContact($valid_contract)
  {
    $this->valid_contract = $valid_contract;
  }

  private function worked_days($payroll)
  {
    $contract = $payroll->consultant_contract;

    $payroll_date = Carbon::create($payroll->consultant_procedure->year, $payroll->consultant_procedure->month->order);

    $start_date = Carbon::parse($contract->start_date . 'T0:0:0');

    $end_date = Carbon::parse($contract->end_date . 'T23:59:59.999999');

    if ($this->retirement_date != null) {
      $retirement_date = Carbon::parse($contract->retirement_date . 'T23:59:59.999999');
      if ($retirement_date->year == $payroll_date->year && $retirement_date->month == $payroll_date->month) {
        $end_date = $retirement_date;
      }
    }

    $last_day_of_month = Carbon::create($payroll_date->year, $payroll_date->month)->endOfMonth()->day;
    $worked_days = 0;

    if (is_null($contract->end_date) && is_null($contract->retirement_date) && $start_date->year <= $payroll_date->year) {
      if ($start_date->year == $payroll_date->year) {
        if ($start_date->month < $payroll_date->month) {
          $worked_days = 30;
        } else {
          $worked_days = 30 - $start_date->day + 1;
        }
      } else {
        $worked_days = 30;
      }
    } else if ($start_date->year == $end_date->year && $start_date->month == $end_date->month) {
      if ($end_date->day == $last_day_of_month && ($last_day_of_month < 30 || $last_day_of_month > 30)) {
        $worked_days = 30 - $start_date->day;
      } else {
        $worked_days = $end_date->day - $start_date->day;
      }
      $worked_days += 1;
    } elseif ($start_date->year <= $payroll_date->year && $start_date->month == $payroll_date->month) {
      $worked_days = 30 - $start_date->day + 1;
    } elseif ($end_date->year >= $payroll_date->year && $end_date->month == $payroll_date->month) {
      $worked_days = $end_date->day;
    } elseif (($start_date->year <= $payroll_date->year && $start_date->month < $payroll_date->month) || ($end_date->year >= $payroll_date->year && $end_date->month > $payroll_date->month)) {
      $worked_days = 30;
    } else {
      $worked_days = 0;
    }
    if ($payroll->unworked_days > $worked_days) {
      return 0;
    } else {
      return ($worked_days - $payroll->unworked_days);
    }
  }
}

