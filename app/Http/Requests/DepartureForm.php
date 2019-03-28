<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartureForm extends FormRequest
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
      case 'POST':
        {
          return [
            'departure_reason_id' => 'required|exists:departure_reasons,id',
            'employee_id' => 'required|exists:employees,id',
            'departure' => 'required|date',
            'return' => 'required|date'
          ];
        }
      case 'PUT':
      case 'PATCH':
        {
          return [];
        }
    }
  }

  public function messages()
  {
    return [
      'departure_reason_id.required' => 'El motivo no puede estar vacío',
      'departure_reason_id.exists' => 'El motivo no existe',
      'employee_id.required' => 'El empleado no puede estar vacío',
      'employee_id.exists' => 'El empleado no existe',
      'departure.required' => 'La fecha de solicitud no puede estar vacía',
      'departure.date' => 'Formato de fecha de solicitud inválido',
      'departure.required' => 'La fecha de retorno no puede estar vacía',
      'departure.date' => 'Formato de fecha de retorno inválido'
    ];
  }
}
