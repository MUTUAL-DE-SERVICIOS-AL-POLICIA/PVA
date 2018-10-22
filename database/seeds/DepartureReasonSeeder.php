<?php

use Illuminate\Database\Seeder;

class DepartureReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
			['departure_type_id' => 1, 'name' => 'Personal', 'day' => 0, 'hour' => 2, 'each' => 'mes', 'pay' => true, 'description' => 'Salida personal con goce de haberes aplicable a 2hrs/mes'],
			['departure_type_id' => 1, 'name' => 'Personal', 'day' => 0, 'hour' => 0, 'each' => '', 'pay' => false, 'description' => 'Salida personal sin goce de haberes'],
			['departure_type_id' => 2, 'name' => 'Viaje', 'day' => 0, 'hour' => 0, 'each' => '', 'pay' => true, 'description' => 'Comision por viaje al interior o exterior'],
			['departure_type_id' => 2, 'name' => 'Reunion', 'day' => 0, 'hour' => 0, 'each' => '', 'pay' => true, 'description' => 'Comision por Reunion'],
			['departure_type_id' => 2, 'name' => 'Taller', 'day' => 0, 'hour' => 0, 'each' => '', 'pay' => true, 'description' => 'Comision por Taller'],
			['departure_type_id' => 2, 'name' => 'Diligencia', 'day' => 0, 'hour' => 0, 'each' => '', 'pay' => true, 'description' => 'Comision por Diligencias'],
			['departure_type_id' => 3, 'name' => 'Matrimonio', 'day' => 3, 'hour' => 0, 'each' => '', 'pay' => true, 'description' => 'Licencia por Matrimonio'],
			['departure_type_id' => 3, 'name' => 'Fallecimiento de familiares', 'day' => 3, 'hour' => 0, 'each' => '', 'pay' => true, 'description' => 'Por fallecimiento de padres, conyugue, hermanos o hijos'],
			['departure_type_id' => 3, 'name' => 'Fallecimiento de parientes politicos', 'day' => 2, 'hour' => 0, 'each' => '', 'pay' => true, 'description' => 'Por fallecimiento de parientes politicos suegros y cuñados'],
			['departure_type_id' => 3, 'name' => 'Nacimiento', 'day' => 3, 'hour' => 0, 'each' => '', 'pay' => true, 'description' => 'Licencia por Nacimiento de hijos'],
			['departure_type_id' => 3, 'name' => 'Academico', 'day' => 0, 'hour' => 0, 'each' => '', 'pay' => true, 'description' => 'Licencia por asistencia a becas, cursos, seminarios y posgrados'],
			['departure_type_id' => 3, 'name' => 'Enfermedad', 'day' => 0, 'hour' => 0, 'each' => '', 'pay' => true, 'description' => 'Licencia por Enfermedad'],
			['departure_type_id' => 3, 'name' => 'Natalidad', 'day' => 90, 'hour' => 0, 'each' => '', 'pay' => true, 'description' => 'Licencia por natalidad 45 dias antes y 45 dias despues del alumbramiento'],
			['departure_type_id' => 3, 'name' => 'Cumpleaños', 'day' => 0, 'hour' => 4, 'each' => '', 'pay' => true, 'description' => 'Licencia por aniversario natal de medio dia'],
			['departure_type_id' => 3, 'name' => 'Personal', 'day' => 2, 'hour' => 0, 'each' => 'anio', 'pay' => true, 'description' => 'Licencia personal 2dias/año'],
			['departure_type_id' => 3, 'name' => 'Cultural o deportiva', 'day' => 0, 'hour' => 0, 'each' => 'variable', 'pay' => true, 'description' => 'Licencia por asistencia cultural o deprtiva en representacion de la institucion'],
			['departure_type_id' => 3, 'name' => 'Jurado Electoral', 'day' => 0, 'hour' => 0, 'each' => '', 'pay' => true, 'description' => 'Licencia por actividades electorales'],
			['departure_type_id' => 3, 'name' => 'Mamografia o papanicolao', 'day' => 0, 'hour' => 0, 'each' => '', 'pay' => true, 'description' => 'Licencia por examenes de mamografia o papanicolao una vez al año'],
			['departure_type_id' => 3, 'name' => 'Otros', 'day' => 0, 'hour' => 0, 'each' => '', 'pay' => false, 'description' => 'Licencia sin goce de haberes'],
			['departure_type_id' => 4, 'name' => 'Otros', 'day' => 0, 'hour' => 0, 'each' => '', 'pay' => false, 'description' => 'Otras salidas']
		];

		foreach ($types as $type) {
			App\DepartureReason::create($type);
		}
    }
}
