<?php

namespace App;

class TotalPayrollEmployer {
	function __construct() {
		$this->quotable = 0;
		$this->contribution_insurance_company = 0;
		$this->contribution_professional_risk = 0;
		$this->contribution_employer_solidary = 0;
		$this->contribution_employer_housing = 0;
		$this->total_contributions = 0;
	}

	public function add_quotable($value) {
		$this->quotable += $value;
	}
	public function add_contribution_insurance_company($value) {
		$this->contribution_insurance_company += $value;
	}
	public function add_contribution_professional_risk($value) {
		$this->contribution_professional_risk += $value;
	}
	public function add_contribution_employer_solidary($value) {
		$this->contribution_employer_solidary += $value;
	}
	public function add_contribution_employer_housing($value) {
		$this->contribution_employer_housing += $value;
	}
	public function add_total_contributions($value) {
		$this->total_contributions += $value;
	}

	public function subtract_quotable($value) {
		$this->quotable -= $value;
	}
	public function subtract_contribution_insurance_company($value) {
		$this->contribution_insurance_company -= $value;
	}
	public function subtract_contribution_professional_risk($value) {
		$this->contribution_professional_risk -= $value;
	}
	public function subtract_contribution_employer_solidary($value) {
		$this->contribution_employer_solidary -= $value;
	}
	public function subtract_contribution_employer_housing($value) {
		$this->contribution_employer_housing -= $value;
	}
	public function subtract_total_contributions($value) {
		$this->total_contributions -= $value;
	}
}
