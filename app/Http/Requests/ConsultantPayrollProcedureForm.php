<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultantPayrollProcedureForm extends FormRequest
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
      'consultant_contract_id' => 'exists:consultant_contracts,id|required|integer',
      'employee_id' => 'exists:employees,id|required|integer',
      'charge_id' => 'exists:charges,id|required|integer',
      'position_group_id' => 'exists:position_groups,id|required|integer',
      'consultant_position_id' => 'exists:consultant_positions,id|required|integer',
    ];
  }

  public function messages()
  {
    return [
      'consultant_contract_id.required' => 'El ID de contrato es requerido',
      'employee_id.required' => 'El ID de empleado es requerido',
      'charge_id.required' => 'El ID de cargo es requerido',
      'position_group_id.required' => 'El ID de unidad es requerido',
      'consultant_position_id.required' => 'El ID de posición es requerido',
      'exists' => 'El registro no existe',
      'integer' => 'El ID debe ser un número entero'
    ];
  }
}
