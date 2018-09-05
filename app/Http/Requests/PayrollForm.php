<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollForm extends FormRequest {
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
			'procedure_id' => 'required',
			'contract_id' => 'required',
			'employee_id' => 'required',
			'position_id' => 'required',
			'charge_id' => 'required',
			'position_group_id' => 'required',
		];
	}
	public function messages() {
		return [
			'required' => 'No puede estar vacio',
		];
	}
}
