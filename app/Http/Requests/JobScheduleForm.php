<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobScheduleForm extends FormRequest
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
            'start_hour'    => 'required',
            'start_minutes' => 'required',
            'end_hour'      => 'required',
            'end_minutes'   => 'required',
        ];
    }
    public function messages()
    {
        return [
            'required' => 'No puede estar vacio',
        ];
    }
}
