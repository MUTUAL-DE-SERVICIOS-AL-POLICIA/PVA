<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcedureForm extends FormRequest
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
            'year'                     => 'required',
            'month_id'                 => 'required',
            'employee_discount_id'     => 'required',
            'employer_contribution_id' => 'required',
            'active'                   => 'required',
        ];
    }
    public function messages()
    {
        return [
            'required' => 'No puede estar vacio',
        ];
    }
}
