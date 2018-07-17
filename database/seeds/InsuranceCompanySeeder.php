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
      ['name' => 'Caja Nacional de Salud', 'shortened' => 'C.N.S.'],
      ['name' => 'Caja Petrolera de Salud', 'shortened' => 'C.P.S.'],
      ['name' => 'Caja de Salud de Caminos', 'shortened' => null],
      ['name' => 'Caja Bancaria Estatal de Salud', 'shortened' => 'C.B.E.S.'],
      ['name' => 'Caja de Salud de la Banca Privada', 'shortened' => 'C.S.B.P.'],
      ['name' => 'Caja de Salud Cordes', 'shortened' => null],
      ['name' => 'Seguro Social Universitario', 'shortened' => 'S.I.S.S.U.B.'],
      ['name' => 'Corporación del Seguro Social Militar', 'shortened' => 'COOSMIL'],
      ['name' => 'Seguro Integral de Salud', 'shortened' => 'SINEC'],
    ];

    foreach($insurance_companies as $insurance_company) {
      App\InsuranceCompany::create($insurance_company);
    }
  }
}
