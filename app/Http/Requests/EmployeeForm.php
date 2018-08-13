<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeForm extends FormRequest
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
            'insurance_company_id' => 'required',
            'city_identity_card_id' => 'required',
            'management_entity_id' => 'required',
            'identity_card' => 'required',
            'first_name' => 'required',
            'gender' => 'required',
            'active' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'required' => 'No puede estar vacio',
        ];
    }
}
