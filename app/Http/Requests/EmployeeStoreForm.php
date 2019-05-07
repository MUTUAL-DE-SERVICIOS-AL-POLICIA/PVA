<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreForm extends FormRequest
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

		return [
			'city_identity_card_id' => 'required',
			'identity_card' => 'required|unique:employees',
			'first_name' => 'required',
			'gender' => 'required',
			'location' => 'required',
			'zone' => 'required',
			'street' => 'required',
			'address_number' => 'required',
			'phone_number' => 'required',
		];
	}

	public function sanitize()
	{
		$input = $this->all();

		$input['gender'] = mb_strtoupper($input['gender']);
		$input['first_name'] = mb_strtoupper($input['first_name']);
		if (array_key_exists('second_name', $input)) {
			$input['second_name'] = mb_strtoupper($input['second_name']);
		}
		if (array_key_exists('last_name', $input)) {
			$input['last_name'] = mb_strtoupper($input['last_name']);
		}

		if (array_key_exists('mothers_last_name', $input)) {
			$input['mothers_last_name'] = mb_strtoupper($input['mothers_last_name']);
		}

		if (array_key_exists('country_birth', $input)) {
			$input['country_birth'] = mb_strtoupper($input['country_birth']);
		}

		$input['location'] = mb_strtoupper($input['location']);
		$input['zone'] = mb_strtoupper($input['zone']);
		$input['street'] = mb_strtoupper($input['street']);
		$input['address_number'] = mb_strtoupper($input['address_number']);

		$this->replace($input);
	}

	public function messages()
	{
		return [
			'required' => 'No puede estar vacio',
			'identity_card.unique' => 'El empleado ya existe',
		];
	}
}
