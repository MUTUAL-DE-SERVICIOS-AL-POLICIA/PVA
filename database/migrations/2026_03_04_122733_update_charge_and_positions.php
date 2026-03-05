<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateChargeAndPositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            //actualizacion de cargos y salarios
            DB::table('charges')->where('name', 'SECRETARIA DE DIRECCION')->update(['name' => 'SECRETARÍA DE DIRECCIÓN EJECUTIVA']);
            DB::table('charges')->where('name', 'DIRECTOR DE AREA')->update(['name' => 'DIRECTOR DE ÁREA    ']);
            DB::table('charges')
                ->update([
                    'name' => DB::raw('TRIM(name)')
                ]);
            DB::table('positions')->where('item', 0)->update(['active' => false]);
            $salarioPorCargo = [
                'DIRECTOR GENERAL EJECUTIVO' => 14878,
                'DIRECTOR DE ÁREA' => 12032,
                'JEFE DE UNIDAD I' => 8626,
                'JEFE DE UNIDAD II' => 7905,
                'RESPONSABLE' => 6972,
                'PROFESIONAL I' => 5622,
                'TÉCNICO I' => 5060,
                'TÉCNICO II' => 4947,
                'SECRETARÍA DE DIRECCIÓN EJECUTIVA' => 4468,
                'TÉCNICO III' => 4426,
                'TÉCNICO IV' => 4385,
                'AUXILIAR I' => 3916,
                'AUXILIAR II' => 3437,
                'AUXILIAR III' => 3300,
            ];
            foreach ($salarioPorCargo as $cargo => $salario) {
                DB::table('charges')
                ->where('name', $cargo)
                ->update(['base_wage' => $salario]);
            }

            //actualizacion de nombres de puestos
            DB::table('positions')->where('item', 15)->where('active',true)->update(['name' => 'Asistente de archivo']);
            DB::table('positions')->where('item', 27)->where('active',true)->update(['name' => 'Responsable de Procesos Técnicos de Patrimonio y Bienes']);
            DB::table('positions')->where('item', 30)->where('active',true)->update(['name' => 'Profesional Administrativo de Hotelería']);
            DB::table('positions')->where('item', 31)->where('active',true)->update(['name' => 'Asistente de recepción I']);
            DB::table('positions')->where('item', 32)->where('active',true)->update(['name' => 'Asistente de recepción II']);
            DB::table('positions')->where('item', 33)->where('active',true)->update(['name' => 'Asistente de recepción III']);
            DB::table('positions')->where('item', 34)->where('active',true)->update(['name' => 'Asistente de recepción IV']);
            DB::table('positions')->where('item', 35)->where('active',true)->update(['name' => 'Asistente Camarero I']);
            DB::table('positions')->where('item', 36)->where('active',true)->update(['name' => 'Asistente Camarero y Mantenimiento']);
            DB::table('positions')->where('item', 39)->where('active',true)->update(['name' => 'Técnico de Archivo y Gestión Documental de Beneficios Económicos']);
            DB::table('positions')->where('item', 41)->where('active',true)->update(['name' => 'Jefe de Unidad de Fondo de Retiro Policial Solidario Cuota y Auxilio Mortuorio']);
            DB::table('positions')->where('item', 43)->where('active',true)->update(['name' => 'Profesional en Calificación Fondo de Retiro, Cuota y Auxilio Mortuorio']);
            DB::table('positions')->where('item', 44)->where('active',true)->update(['name' => 'Técnico de Atención al Afiliado de Fondo de Retiro']);
            DB::table('positions')->where('item', 48)->where('active',true)->update(['name' => 'Jefe de Unidad del Complemento Económico']);
            DB::table('positions')->where('item', 54)->where('active',true)->update(['name' => 'Técnico de Organización y Logística']);
            DB::table('positions')->where('item', 57)->where('active',true)->update(['name' => 'Jefe de la Unidad Financiera']);
            DB::table('positions')->where('item', 61)->where('active',true)->update(['name' => 'Técnico de Archivo Contable']);
            DB::table('positions')->where('item', 67)->where('active',true)->update(['name' => 'Responsable de Presupuesto']);
            DB::table('positions')->where('item', 68)->where('active',true)->update(['name' => 'Técnico de Presupuesto']);
            DB::table('positions')->where('item', 69)->where('active',true)->update(['name' => 'Jefe de la Unidad Administrativa']);
            DB::table('positions')->where('item', 71)->where('active',true)->update(['name' => 'Profesional en Servicios Generales']);
            DB::table('positions')->where('item', 87)->where('active',true)->update(['name' => 'Representante Departamental de Santa Cruz']);
            DB::table('positions')->where('item', 89)->where('active',true)->update(['name' => 'Representante Departamental de Cochabamba']);
            DB::table('positions')->where('item', 91)->where('active',true)->update(['name' => 'Representante Departamental de Oruro']);
            DB::table('positions')->where('item', 92)->where('active',true)->update(['name' => 'Representante Departamental de Chuquisaca']);
            DB::table('positions')->where('item', 93)->where('active',true)->update(['name' => 'Representante Departamental de Potosí']);
            DB::table('positions')->where('item', 94)->where('active',true)->update(['name' => 'Representante Departamental de Tarija']);
            DB::table('positions')->where('item', 95)->where('active',true)->update(['name' => 'Representante Departamental de Beni']);
            DB::table('positions')->where('item', 96)->where('active',true)->update(['name' => 'Representante Departamental de Pando']);
        } catch (\Exception $e) {
            echo "Error al actualizar cargos y posiciones: " . $e->getMessage();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
