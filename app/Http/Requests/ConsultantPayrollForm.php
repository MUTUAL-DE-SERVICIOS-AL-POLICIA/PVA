<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultantPayrollForm extends FormRequest
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
			'consultant_procedure_id' => 'required',
			'consultant_contract_id' => 'required',
			'employee_id' => 'required',
			'consultant_position_id' => 'required',
			'charge_id' => 'required',
			'position_group_id' => 'required',
		];
	}
	public function messages()
	{
		return [
			'required' => 'No puede estar vacio',
		];
	}
}
