<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationForm extends FormRequest
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

    switch ($this->method()) {
      case 'POST':
        {
          return [
            'name' => 'required|min:1',
            'phone_number' => 'required|integer',
            'position_group_id' => 'required|integer'
          ];
        }
      case 'PUT':
      case 'PATCH':
        {
          return [
            'name' => 'min:1'
          ];
        }
    }
  }

  public function sanitize()
  {
    $input = $this->all();
    $input['name'] = strtoupper($input['name']);
    $this->replace($input);
  }

  public function messages()
  {
    return [
      'name.required' => 'El nombre no puede estar vacío',
      'phone_number.required' => 'El número de telefono no puede estar vacío',
      'position_group.required' => 'El área no puede estar vacía',
      'integer' => 'Debe contener solo números',
      'min' => 'Valor demasiado pequeño',
    ];
  }
}
