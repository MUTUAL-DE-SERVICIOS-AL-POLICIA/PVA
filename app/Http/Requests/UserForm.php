<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserForm extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'oldPassword' => 'required|min:5|max:255',
			'newPassword' => 'required|min:5|max:255',
		];
	}

	public function messages()
	{
		return [
			'required' => 'La contraseña no puede estar vacía',
			'min' => 'El número mínimo de caracteres es 5',
			'max' => 'El número máximo de caracteres es 255',
		];
	}
}
