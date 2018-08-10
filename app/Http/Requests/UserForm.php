<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserForm extends FormRequest {
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
			'password' => 'required|min:5|max:255',
		];
	}

	public function messages() {
		return [
			'password.required' => 'La contraseña no puede estar vacía',
			'password.min' => 'El número mínimo de caracteres es 5',
			'password.max' => 'El número máximo de caracteres es 255',
		];
	}
}
