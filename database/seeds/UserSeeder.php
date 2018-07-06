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
    App\User::create([
      'username' => 'admin',
      'password' => bcrypt('admin'),
    ]);
    factory(App\User::class, 5)->create();
  }
}
