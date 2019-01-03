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
            ['id'=>1, 'departure_type_id' => 1, 'name' => 'Personal', 'day' => null, 'hour' => 2, 'each' => 'mes', 'pay' => true, 'description' => 'Para asuntos de índole personal podran usar un máximo de 2 horas en el mes en salidas particulares en horas de oficina, las que no serán acumulativas.'],
            ['id'=>2, 'departure_type_id' => 1, 'name' => 'Comisión por viaje', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Comisión por viaje'],
            ['id'=>3, 'departure_type_id' => 1, 'name' => 'Comisión por reunión', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Comisión por reunión'],
            ['id'=>4, 'departure_type_id' => 1, 'name' => 'Comisión por curso/taller', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Comisión por curso/taller'],
            ['id'=>5, 'departure_type_id' => 1, 'name' => 'Comisión por diligencia', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Comisión por diligencia'],
            ['id'=>6, 'departure_type_id' => 1, 'name' => 'Salida a la CNS', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Salidas para atención médica serán aceptadas con el registro de la hora de ingreso, salida y la firma del médico que le atendió, del ente gestor al que esta afiliado.'],            
            ['id'=>7, 'departure_type_id' => 1, 'name' => 'Otros', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Otros motivos'],
            

            ['id'=>8, 'departure_type_id' => 2, 'name' => 'Fallecimiento de padres, conyuge, hermanos o hijos', 'day' => 3, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Por fallecimiento de padres, cónyuge, hermanos o hijos, gozará de (3) días hábilies de licencia, debiendo presentar certificado de defuncion hasta los 5 dias de finalizada la licencia.'],
            ['id'=>9, 'departure_type_id' => 2, 'name' => 'Fallecimiento de suegros o cuñados', 'day' => 2, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Por fallecimiento de suegros y cuñados, gozará de (2) días hábiles de licencia, debiendo presentar certificado de defuncion hasta los 5 dias de finalizada la licencia.'],
            ['id'=>10, 'departure_type_id' => 2, 'name' => 'Nacimiento de hijos', 'day' => 3, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Por nacimiento de hijos, el padre gozará de (3) días de hábiles de licencia, con obligación de presentar el certificado de nacimiento correspondiente'],
            ['id'=>11, 'departure_type_id' => 2, 'name' => 'Asistencia a becas, cursos, seminarios, posgrado', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Siempre que se encuentre vinculada al logro de objetivos establecidos en el Programa de Operaciones Anual Institucional'],
            ['id'=>12, 'departure_type_id' => 2, 'name' => 'Enfermedad', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Enfermedad'],
            ['id'=>13, 'departure_type_id' => 2, 'name' => 'Maternidad', 'day' => 90, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Baja prenatal y posnatal por un periodo de 90 días calendario, 45 días antes y 45 días despues del alumbramiento'],
            ['id'=>14, 'departure_type_id' => 2, 'name' => 'Matrimonio', 'day' => 3, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Por matrimonio, gozará de (3) días hábiles de licencia, previa presentación de la certificación de inscripción expedida por el oficial de registro civil que acredite la fecha de realización del matrimonio.'],
            ['id'=>15, 'departure_type_id' => 2, 'name' => 'Cumpleaños', 'day' => null, 'hour' => 4, 'each' => null, 'pay' => true, 'description' => 'Por aniversario natal de las o los servidores gozarán de medio día, cuando la fecha coincida con un día laborable.'],
            ['id'=>16, 'departure_type_id' => 2, 'name' => 'Personal', 'day' => 2, 'hour' => null, 'each' => 'anio', 'pay' => true, 'description' => 'Para la resolución de asuntos de indole personal, se otorgarán 2 días hábiles fraccionados en el transcurso de 1 año, previa autorización del inmediato superior, los cuales no podrán ser consecutivos (ni anteriores o posteriores) a las vacaciones o feriados.'],
            ['id'=>17, 'departure_type_id' => 2, 'name' => 'Actividad cultural o deportiva', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'A las o los servidores que cumplan con actividades culturales o deportivas que sean realizadas en el país en representación de la institución, se les otorgará las licencias respectivas mediante comunicación interna suscrita por el jefe de la Unidad de Recursos Humanos'],
            ['id'=>18, 'departure_type_id' => 2, 'name' => 'Jurado electoral', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'En caso de haber sido designado jurado electoral'],
            ['id'=>19, 'departure_type_id' => 2, 'name' => 'Mamografía/Papanicolao', 'day' => 1, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Las servidoras públicas gozarán de un día para examenes de Mamogradía y Papanicolao'],
            ['id'=>20, 'departure_type_id' => 2, 'name' => 'Compensación', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Por Compensación'],
            ['id'=>21, 'departure_type_id' => 2, 'name' => 'Otros c/g Haberes', 'day' => null, 'hour' => null, 'each' => null, 'pay' => true, 'description' => 'Otros motivos con justificación aprobada por la autoridad correspondiente'],
            ['id'=>22, 'departure_type_id' => 2, 'name' => 'Otros s/g Haberes', 'day' => null, 'hour' => null, 'each' => null, 'pay' => false, 'description' => 'Otros motivos sin goce de haberes con un maximo de 10 días habiles ']
        ];

        foreach ($types as $type) {
            App\DepartureReason::create($type);
        }
    }
}
