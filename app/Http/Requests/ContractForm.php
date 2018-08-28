<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractForm extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'employee_id' => 'required',
			'position_id' => 'required',
			'contract_type_id' => 'required',
			'contract_mode_id' => 'required',
			'start_date' => 'required',
			'insurance_company_id' => 'required',
		];
	}
	public function messages() {
		return [
			'required' => 'No puede estar vacio',
		];
	}
}
