<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon;

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
              $departureRules = ['required', 'date'];

              if (in_array($this->departure_reason_id, [1, 4, 6, 7, 8])) {
                  $departureRules[] = 'after:' . now()->format('Y-m-d H:i:s');
              }

              return [
                  'departure_reason_id' => 'required|exists:departure_reasons,id',
                  'employee_id' => 'required|exists:employees,id',
                  'departure' => $departureRules,
                  'return' => 'required|date|after:departure',
              ];

          case 'PUT':
          case 'PATCH':
              return [];

          default:
              return [];
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
      'departure.date' => 'Formato de fecha de retorno inválido',
      'departure.after' => 'La fecha y hora de salida debe ser antes de la fecha y hora actual: '.Carbon::now()
    ];
  }
}
