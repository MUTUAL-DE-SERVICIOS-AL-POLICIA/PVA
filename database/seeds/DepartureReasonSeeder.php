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
            ['id'=>1, 'departure_type_id' => 1, 'name' => 'Personal', 'day' => null, 'hour' => 2, 'each' => 'mes', 'pay' => true, 'description' => 'Para asuntos de indole personal con un maximo de 2 horas en el mes'],
            ['id'=>2, 'departure_type_id' => 1, 'name' => 'Comision por viaje', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Comisión por viaje'],
            ['id'=>3, 'departure_type_id' => 1, 'name' => 'Comision por reunion', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Comisión por reunión'],
            ['id'=>4, 'departure_type_id' => 1, 'name' => 'Comision por curso/taller', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Comisión por curso/taller'],
            ['id'=>5, 'departure_type_id' => 1, 'name' => 'Comision por diligencia', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Comisión por diligencia'],
            ['id'=>6, 'departure_type_id' => 1, 'name' => 'Salida a la CNS', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Salidas para atención médica con hora de ingreso, salida y la firma del medico'],
            ['id'=>7, 'departure_type_id' => 1, 'name' => 'Cumpleaños', 'day' => null, 'hour' => 4, 'each' => null, 'pay' => true, 'description' => 'Medio dia de tolerancia, presentar fotocopia de C.I.'],
            ['id'=>8, 'departure_type_id' => 1, 'name' => 'Otros', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Otros motivos'],
            

            ['id'=>9, 'departure_type_id' => 2, 'name' => 'Fallecimiento de padres, conyuge, hermanos o hijos', 'day' => 3, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Por fallecimiento de padres, conyuge, hermanos o hijos, debiendo presentar certificado de defuncion hasta los 5 dias de finalizada la licencia (3 dias hábiles.)'],
            ['id'=>10, 'departure_type_id' => 2, 'name' => 'Fallecimiento de suegros o cuñados', 'day' => 2, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Por fallecimiento de suegros y cuñados, debiendo presentar certificado de defuncion hasta los 5 dias de finalizada la licencia. (2 días hábiles)'],
            ['id'=>11, 'departure_type_id' => 2, 'name' => 'Nacimiento de hijos', 'day' => 3, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Nacimiento de hijos, con obligacion de presentar certificado de nacimiento correspondiente. (3 días hábiles)'],
            ['id'=>12, 'departure_type_id' => 2, 'name' => 'Asistencia a becas, cursos, seminarios, posgrado', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Siempre que se encuentre vinculada al logro de objetivos establecidos en el Programa de Operaciones Anual Institucional'],
            ['id'=>13, 'departure_type_id' => 2, 'name' => 'Enfermedad', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Enfermedad'],
            ['id'=>14, 'departure_type_id' => 2, 'name' => 'Maternidad', 'day' => 90, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Baja prenatal y posnatal por un periodo de 90 dias calendario, 45 dias antes y 45 dias despues del alumbramiento'],
            ['id'=>15, 'departure_type_id' => 2, 'name' => 'Matrimonio', 'day' => 3, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Gozara de 3 dias habiles de licencia previa presentacion de la certificación de inscripción expedida por el oficial de registro civil'],
            ['id'=>16, 'departure_type_id' => 2, 'name' => 'Personal', 'day' => 2, 'hour' => null, 'each' => 'anio', 'pay' => true, 'description' => 'Para la resolución de asuntos de indole personal, previa autorización del inmediato superior, los cuales no podrán ser consecutivos ni anteriores o posteriores a las vacaciones o feriados. (2 días hábiles por año)'],
            ['id'=>17, 'departure_type_id' => 2, 'name' => 'Actividad cultural o deportiva', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Actividades que sean realizadas en el país en representación de la institución'],
            ['id'=>18, 'departure_type_id' => 2, 'name' => 'Jurado electoral', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'En caso de haber sido designado jurado electoral'],
            ['id'=>19, 'departure_type_id' => 2, 'name' => 'Mamografia/Papanicolao', 'day' => 1, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Las servidoras públicas gozarán de un día'],
            ['id'=>20, 'departure_type_id' => 2, 'name' => 'Compensación', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Por Compensación'],
            ['id'=>21, 'departure_type_id' => 2, 'name' => 'Otros c/g Haberes', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Otros motivos con justificación aprobada por la autoridad correspondiente'],
            ['id'=>22, 'departure_type_id' => 2, 'name' => 'Otros s/g Haberes', 'day' => null, 'hour' => null, 'each' => null, 'pay' => false, 'description' => 'Otros motivos sin goce de haberes con un maximo de 10 días habiles ']
        ];

        foreach ($types as $type) {
            App\DepartureReason::create($type);
        }
    }
}
