<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeDiscountForm extends FormRequest {
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
			'elderly' => 'required|between:0,99.99',
			'common_risk' => 'required|between:0,99.99',
			'comission' => 'required|between:0,99.99',
			'solidary' => 'required|between:0,99.99',
			'national' => 'required|between:0,99.99',
			'rc_iva' => 'required|between:0,99.99',
		];
	}

	public function messages() {
		return [
			'required' => 'El campo es requerido',
			'between' => 'El rango porcentual es de 0% a 99.99%',
		];
	}
}
