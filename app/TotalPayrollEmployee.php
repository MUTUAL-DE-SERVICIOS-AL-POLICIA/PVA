<?php

namespace App;

class TotalPayrollEmployee {
	function __construct() {
		$this->base_wage = 0;
		$this->quotable = 0;
		$this->discount_old = 0;
		$this->discount_common_risk = 0;
		$this->discount_commission = 0;
		$this->discount_solidary = 0;
		$this->discount_national = 0;
		$this->total_amount_discount_law = 0;
		$this->net_salary = 0;
		$this->discount_rc_iva = 0;
		$this->total_discounts = 0;
		$this->payable_liquid = 0;
	}

	public function add_base_wage($value) {
		$this->base_wage += $value;
	}
	public function add_quotable($value) {
		$this->quotable += $value;
	}
	public function add_discount_old($value) {
		$this->discount_old += $value;
	}
	public function add_discount_common_risk($value) {
		$this->discount_common_risk += $value;
	}
	public function add_discount_commission($value) {
		$this->discount_commission += $value;
	}
	public function add_discount_solidary($value) {
		$this->discount_solidary += $value;
	}
	public function add_discount_national($value) {
		$this->discount_national += $value;
	}
	public function add_total_amount_discount_law($value) {
		$this->total_amount_discount_law += $value;
	}
	public function add_net_salary($value) {
		$this->net_salary += $value;
	}
	public function add_discount_rc_iva($value) {
		$this->discount_rc_iva += $value;
	}
	public function add_total_discounts($value) {
		$this->total_discounts += $value;
	}
	public function add_payable_liquid($value) {
		$this->payable_liquid += $value;
	}

	public function subtract_base_wage($value) {
		$this->base_wage -= $value;
	}
	public function subtract_quotable($value) {
		$this->quotable -= $value;
	}
	public function subtract_discount_old($value) {
		$this->discount_old -= $value;
	}
	public function subtract_discount_common_risk($value) {
		$this->discount_common_risk -= $value;
	}
	public function subtract_discount_commission($value) {
		$this->discount_commission -= $value;
	}
	public function subtract_discount_solidary($value) {
		$this->discount_solidary -= $value;
	}
	public function subtract_discount_national($value) {
		$this->discount_national -= $value;
	}
	public function subtract_total_amount_discount_law($value) {
		$this->total_amount_discount_law -= $value;
	}
	public function subtract_net_salary($value) {
		$this->net_salary -= $value;
	}
	public function subtract_discount_rc_iva($value) {
		$this->discount_rc_iva -= $value;
	}
	public function subtract_total_discounts($value) {
		$this->total_discounts -= $value;
	}
	public function subtract_payable_liquid($value) {
		$this->payable_liquid -= $value;
	}
}
