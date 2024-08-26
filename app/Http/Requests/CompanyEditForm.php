<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyEditForm extends FormRequest
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
    switch ($this->method()) {
      case 'POST': {
          return [
            'name' => 'required|min:3',
            'shortened' => 'required|min:3|max:255',
            'tax_number' => 'required|min:1|max:9223372036854775808',
            'directors_designation_number' => 'required|min:1|max:9223372036854775808',
            'directors_designation_date' => 'required|date',
          ];
        }
      case 'PUT':
      case 'PATCH': {
          return [
            'name' => 'nullable|min:3',
            'shortened' => 'nullable|min:3|max:255',
            'tax_number' => 'nullable|min:1|max:9223372036854775808',
            'directors_designation_number' => 'nullable|min:1|max:9223372036854775808',
            'directors_designation_date' => 'nullable|date',
          ];
        }
    }
  }

  public function messages()
  {
    return [
      'required' => 'No puede estar vacio',
      'name.min' => 'El número mínimo de caracteres es 3',
      'shortened.min' => 'El número mínimo de caracteres es 3',
      'shortened.max' => 'El número máximo de caracteres es 255',
      'tax_number.min' => 'El número mínimo de caracteres es 1',
      'tax_number.max' => 'El número máximo de caracteres es 9223372036854775808',
      'directors_designation_number.min' => 'El número mínimo de caracteres es 1',
      'directors_designation_number.max' => 'El número máximo de caracteres es 9223372036854775808'
    ];
  }
}
