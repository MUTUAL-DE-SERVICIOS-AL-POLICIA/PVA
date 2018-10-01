<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayrollsExport implements FromCollection, WithHeadings
{
  use Exportable;

  protected $data;

  public function __construct($data, $headers)
  {
    $this->data = $data;
    $this->headers = $headers;
  }

  public function collection()
  {
    return collect($this->data);
  }

  public function headings() : array
  {
    return $this->headers;
  }
}
