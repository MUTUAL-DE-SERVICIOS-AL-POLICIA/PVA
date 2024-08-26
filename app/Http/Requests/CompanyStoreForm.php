<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreForm extends FormRequest {
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
			'name' => 'required|unique:companies|min:3',
			'shortened' => 'required|unique:companies|min:3|max:255',
			'tax_number' => 'required|min:1|max:9223372036854775808',
		];
	}

	public function messages() {
		return [
			'name.required' => 'El campo de nombre no puede estar vacío',
			'name.unique' => 'El nombre de Compañia ya está en uso',
			'name.min' => 'El número mínimo de caracteres es 3',
			'shortened.required' => 'El campo de sigla no puede estar vacío',
			'shortened.unique' => 'La sigla ya está en uso',
			'shortened.min' => 'El número mínimo de caracteres es 3',
			'tax_number.required' => 'El código de impuestos no puede estar vacío',
			'tax_number.min' => 'El número mínimo de caracteres es 1',
			'tax_number.max' => 'El número máximo de caracteres es 9223372036854775808',
		];
	}
}
