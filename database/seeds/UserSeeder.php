<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$user = App\User::create([
			'username' => 'admin',
			'password' => bcrypt('admin'),
		]);

		$role = App\Role::create([
			'name' => 'admin',
			'display_name' => 'Administrador',
			'description' => 'Administrador',
		]);

		$user->attachRole($role);

		$role = App\Role::create([
			'name' => 'rrhh',
			'display_name' => 'RR.HH.',
			'description' => 'Gestión de Empleados',
		]);

		$role = App\Role::create([
			'name' => 'juridica',
			'display_name' => 'Juridica',
			'description' => 'Gestión de Contratos',
		]);

		$role = App\Role::create([
			'name' => 'financiera',
			'display_name' => 'Financiera',
			'description' => 'Impresión de Planillas',
		]);

		$role = App\Role::create([
			'name' => 'almacenes',
			'display_name' => 'Almacenes',
			'description' => 'Gestión de almacenes',
		]);

		if (config('app.debug')) {
			factory(App\User::class, 5)->create();
		}
	}
}
