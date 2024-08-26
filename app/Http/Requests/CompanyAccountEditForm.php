<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyAccountEditForm extends FormRequest {
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
			'account' => 'unique:company_accounts|min:3|max:9223372036854775808',
			'financial_entity' => 'min:3|max:255',
			'description' => 'nullable',
		];
	}

	public function messages() {
		return [
			'account.unique' => 'La cuenta ya está en uso',
			'account.min' => 'El número mínimo de caracteres es 3',
			'financial_entity.min' => 'El número mínimo de caracteres es 3',
			'financial_entity.max' => 'El número mínimo de caracteres es 255',
		];
	}
}
