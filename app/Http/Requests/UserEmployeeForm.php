<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEmployeeForm extends FormRequest
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
      'employee_id' => 'exists:employees,id|required|integer',
    ];
  }

  public function messages()
  {
    return [
      'required' => 'El empleado es requerido',
      'exists' => 'El empleado no existe',
      'integer' => 'El ID debe ser un número entero'
    ];
  }
}
