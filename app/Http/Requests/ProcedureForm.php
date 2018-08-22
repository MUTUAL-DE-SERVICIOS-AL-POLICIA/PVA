<?php

namespace App\Http\Requests;

use App\Procedure;
use Illuminate\Foundation\Http\FormRequest;

class ProcedureForm extends FormRequest {
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
			'year' => 'required',
			'month_id' => 'required|exists:months,id',
		];
	}

	/**
	 * Validate request
	 * @return
	 */
	public function validate() {
		if (Procedure::where('year', $this->input('year'))->where('month_id', $this->input('month_id'))->count() == 0) {
			return parent::validate();
		}
		return abort(400);
	}

	public function messages() {
		return [
			'required' => 'No puede estar vacio',
			'exists' => 'No existe la referencia',
		];
	}
}
