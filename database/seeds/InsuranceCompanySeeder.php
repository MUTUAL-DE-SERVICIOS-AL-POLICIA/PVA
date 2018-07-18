<?php

use Illuminate\Database\Seeder;

class InsuranceCompanySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $insurance_companies = [
      ['ovt_id' => 1, 'name' => 'Caja Nacional de Salud', 'shortened' => 'C.N.S.'],
      ['ovt_id' => 2, 'name' => 'Caja Petrolera de Salud', 'shortened' => 'C.P.S.'],
      ['ovt_id' => 3, 'name' => 'Caja de Salud de Caminos', 'shortened' => null],
      ['ovt_id' => 4, 'name' => 'Caja Bancaria Estatal de Salud', 'shortened' => 'C.B.E.S.'],
      ['ovt_id' => 5, 'name' => 'Caja de Salud de la Banca Privada', 'shortened' => 'C.S.B.P.'],
      ['ovt_id' => 6, 'name' => 'Caja de Salud Cordes', 'shortened' => null],
      ['ovt_id' => 7, 'name' => 'Seguro Social Universitario', 'shortened' => 'S.I.S.S.U.B.'],
      ['ovt_id' => 8, 'name' => 'Corporación del Seguro Social Militar', 'shortened' => 'COOSMIL'],
      ['ovt_id' => 9, 'name' => 'Seguro Integral de Salud', 'shortened' => 'SINEC'],
    ];

    foreach($insurance_companies as $insurance_company) {
      App\InsuranceCompany::create($insurance_company);
    }
  }
}
