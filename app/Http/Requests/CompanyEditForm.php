<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyEditForm extends FormRequest {
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
			'name' => 'min:3',
			'shortened' => 'min:3|max:255',
			'tax_number' => 'min:1|max:9223372036854775808',
		];
	}

	public function messages() {
		return [
			'name.min' => 'El número mínimo de caracteres es 3',
			'shortened.min' => 'El número mínimo de caracteres es 3',
			'shortened.max' => 'El número máximo de caracteres es 255',
			'tax_number.min' => 'El número mínimo de caracteres es 1',
			'tax_number.max' => 'El número máximo de caracteres es 9223372036854775808',
		];
	}
}
