<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyAddressEditForm extends FormRequest {
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
			'address' => 'unique:company_addresses|min:3|max:255',
			'city_id' => 'exists:cities,id',
		];
	}

	public function messages() {
		return [
			'address.unique' => 'La dirección ya existe',
			'address.min' => 'El número mínimo de caracteres es 3',
			'address.max' => 'El número máximo de caracteres es 255',
			'city_id.exists' => 'El ID de la ciudad no existe',
		];
	}
}
