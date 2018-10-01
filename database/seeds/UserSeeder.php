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
		$role = App\Role::create([
			'name' => 'admin',
			'display_name' => 'Administrador',
			'description' => 'Administrador',
		]);

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

		$user = App\User::create([
			'username' => 'admin',
			'password' => bcrypt('admin'),
		]);

		$user->attachRole($role);

		if (config('app.debug')) {
			$permission = App\Permission::create([
				'name' => 'view',
				'display_name' => 'Vista',
				'description' => 'Permiso para obtener datos sin modificarlos',
			]);

			$role = App\Role::create([
				'name' => 'guest',
				'display_name' => 'Invitado',
				'description' => 'Rol invitado',
			]);

			$role->attachPermission($permission);

			$user = App\User::create([
				'username' => 'guest',
				'password' => bcrypt('guest'),
			]);

			$user->attachRole($role);

			factory(App\User::class, 5)->create();
		}
	}
}
