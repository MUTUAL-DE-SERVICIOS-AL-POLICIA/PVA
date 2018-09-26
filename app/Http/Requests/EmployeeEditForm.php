<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeEditForm extends FormRequest
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
		$this->sanitize();

		return [];
	}

	public function sanitize()
	{
		$input = $this->all();

		if (array_key_exists('gender', $input)) {
			$input['gender'] = strtoupper($input['gender']);
		}
		if (array_key_exists('first_name', $input)) {
			$input['first_name'] = strtoupper($input['first_name']);
		}
		if (array_key_exists('second_name', $input)) {
			$input['second_name'] = strtoupper($input['second_name']);
		}
		if (array_key_exists('last_name', $input)) {
			$input['last_name'] = strtoupper($input['last_name']);
		}
		if (array_key_exists('mothers_last_name', $input)) {
			$input['mothers_last_name'] = strtoupper($input['mothers_last_name']);
		}
		if (array_key_exists('country_birth', $input)) {
			$input['country_birth'] = strtoupper($input['country_birth']);
		}
		if (array_key_exists('location', $input)) {
			$input['location'] = strtoupper($input['location']);
		}
		if (array_key_exists('zone', $input)) {
			$input['zone'] = strtoupper($input['zone']);
		}
		if (array_key_exists('street', $input)) {
			$input['street'] = strtoupper($input['street']);
		}
		if (array_key_exists('address_number', $input)) {
			$input['address_number'] = strtoupper($input['address_number']);
		}

		$this->replace($input);
	}

	public function messages()
	{
		return [];
	}
}
