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
    // Personal
    $group = App\DepartureGroup::where('name', 'Personal')->first();
    $group->departure_reasons()->saveMany([
      new App\DepartureReason(['name' => 'Permiso por horas', 'days' => null, 'hours' => 2, 'reset' => 'monthly', 'payable' => true, 'note' => false, 'description_needed' => false, 'description' => 'Para asuntos de índole personal podrá solicitar 30 minutos, 1 hora o 1 hora y media, sumando un máximo de 2 permisos a partir del día 20 de cada mes.']),
      new App\DepartureReason(['name' => 'Cumpleaños', 'days' => null, 'hours' => 4, 'reset' => 'annually', 'payable' => true, 'note' => false, 'description_needed' => false, 'description' => 'Las y los servidores gozarán de medio día cuando la fecha de su cumpleaños coincida con un día laboral.']),
      new App\DepartureReason(['name' => 'Licencia con goce de haberes', 'days' => null, 'hours' => 16, 'reset' => 'annually', 'payable' => true, 'note' => true, 'description_needed' => false, 'description' => 'Para la resolución de asuntos de indole personal podrá solicitar 2 días hábiles fraccionados en el transcurso de 1 año, previa autorización del inmediato superior, los cuales no podrán ser consecutivos, ni anteriores o posteriores a las vacaciones o feriados.']),
      new App\DepartureReason(['name' => 'Licencia sin goce de haberes', 'days' => null, 'hours' => null, 'reset' => 'annually', 'payable' => false, 'note' => true, 'description_needed' => false, 'description' => 'Otros motivos sin goce de haberes con un maximo de 10 días habiles ']),
      new App\DepartureReason(['name' => 'Regularización de marcado', 'days' => null, 'hours' => 3, 'reset' => 'monthly', 'payable' => true, 'note' => true, 'description_needed' => false, 'description' => 'Regulación por olvido de marcado al ingreso o salida.'])
    ]);

    // Comisión
    $group = App\DepartureGroup::where('name', 'Comisión')->first();
    $group->departure_reasons()->saveMany([
      new App\DepartureReason(['name' => 'Diligencia', 'days' => null, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => false, 'description_needed' => true, 'description' => 'Comisión por diligencia']),
      new App\DepartureReason(['name' => 'Reunión', 'days' => null, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => false, 'description_needed' => true, 'description' => 'Comisión por reunión']),
      new App\DepartureReason(['name' => 'Curso/taller', 'days' => null, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => false, 'description_needed' => true, 'description' => 'Comisión por curso/taller']),
      new App\DepartureReason(['name' => 'Viaje', 'days' => null, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => false, 'description_needed' => false, 'description' => 'Comisión por viaje'])
    ]);

    // Salud
    $group = App\DepartureGroup::where('name', 'Salud')->first();
    $group->departure_reasons()->saveMany([
      new App\DepartureReason(['name' => 'Consulta médica', 'days' => null, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => false, 'description_needed' => false, 'description' => 'Salidas para atención médica serán aceptadas con el registro de la hora de ingreso, salida y la firma del médico que le atendió.']),
      new App\DepartureReason(['name' => 'Baja médica', 'days' => null, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => false, 'description_needed' => false, 'description' => 'Baja por enfermedad']),
      new App\DepartureReason(['name' => 'Mamografía/Papanicolao', 'days' => null, 'hours' => 8, 'reset' => 'annually', 'payable' => true, 'note' => true, 'description_needed' => false, 'description' => 'Las servidoras públicas gozarán de 1 día para exámenes de mamogradía y papanicolau que puede ser fraccionado en 2 medias jornadas.']),
      new App\DepartureReason(['name' => 'Examen de próstata', 'days' => null, 'hours' => 8, 'reset' => 'annually', 'payable' => true, 'note' => true, 'description_needed' => false, 'description' => 'Los servidores públicos gozarán de 1 día para exámenes de próstata que puede ser fraccionado en 2 medias jornadas.']),
      new App\DepartureReason(['name' => 'Examen de colón', 'days' => null, 'hours' => 8, 'reset' => 'annually', 'payable' => true, 'note' => true, 'description_needed' => false, 'description' => 'Las y los servidores públicos gozarán de un 1 para exámenes de colón que puede ser fraccionado en 2 medias jornadas.'])
    ]);

    // Familiar
    $group = App\DepartureGroup::where('name', 'Familiar')->first();
    $group->departure_reasons()->saveMany([
      new App\DepartureReason(['name' => 'Matrimonio', 'days' => 3, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => true, 'description_needed' => false, 'description' => 'Por matrimonio, gozará de 3 días hábiles de licencia, previa presentación de la certificación de inscripción expedida por el oficial de registro civil que acredite la fecha de realización del matrimonio.']),
      new App\DepartureReason(['name' => 'Nacimiento de hijos', 'days' => 3, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => true, 'description_needed' => false, 'description' => 'Por nacimiento de hijos, el padre gozará de 3 días de hábiles de licencia, con obligación de presentar el certificado de nacimiento correspondiente']),
      new App\DepartureReason(['name' => 'Maternidad', 'days' => 90, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => false, 'description_needed' => false, 'description' => 'Baja prenatal y posnatal por un periodo de 90 días calendario, 45 días antes y 45 días despues del alumbramiento']),
      new App\DepartureReason(['name' => 'Fallecimiento de padres, conyuge, hermanos o hijos', 'days' => 3, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => true, 'description_needed' => false, 'description' => 'Por fallecimiento de padres, cónyuge, hermanos o hijos, gozará de 3 días hábilies de licencia, debiendo presentar certificado de defuncion hasta los 5 dias de finalizada la licencia.']),
      new App\DepartureReason(['name' => 'Fallecimiento de suegros o cuñados', 'days' => 2, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => true, 'description_needed' => false, 'description' => 'Por fallecimiento de suegros y cuñados, gozará de 2 días hábiles de licencia, debiendo presentar certificado de defuncion hasta los 5 dias de finalizada la licencia.'])
    ]);

    // Extracurricular
    $group = App\DepartureGroup::where('name', 'Extracurricular')->first();
    $group->departure_reasons()->saveMany([
      new App\DepartureReason(['name' => 'Tolerancia para docencia, becas, cursos, seminarios, postgrados', 'days' => null, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => true, 'description_needed' => false, 'description' => 'Siempre que se encuentre vinculada al logro de objetivos establecidos en el Programa de Operaciones Anual Institucional']),
      new App\DepartureReason(['name' => 'Jurado electoral', 'days' => 1, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => false, 'description_needed' => false, 'description' => 'En caso de haber sido designado jurado electoral']),
      new App\DepartureReason(['name' => 'Actividad cultural o deportiva', 'days' => null, 'hours' => null, 'reset' => null, 'payable' => true, 'note' => false, 'description_needed' => false, 'description' => 'A las o los servidores que cumplan con actividades culturales o deportivas que sean realizadas en el país en representación de la institución, se les otorgará las licencias respectivas mediante comunicación interna suscrita por el jefe de la Unidad de Recursos Humanos'])
    ]);
  }
}
