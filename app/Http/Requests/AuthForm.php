<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthForm extends FormRequest {
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
			'username' => 'required|min:5|max:255',
			'password' => 'required|min:5|max:255',
		];
	}

	public function messages() {
		return [
			'username.required' => 'El campo de usuario no puede estar vacío',
			'username.min' => 'El número mínimo de caracteres es 5',
			'username.max' => 'El número máximo de caracteres es 255',
			'password.required' => 'El campo de contraseña no puede estar vacío',
			'password.min' => 'El número mínimo de caracteres es 5',
			'password.max' => 'El número máximo de caracteres es 255',
		];
	}
}
