<?php

use Illuminate\Database\Seeder;
use App\User;

class TruncateUserPermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $users = User::where('username', '!=', 'admin')->get();
    foreach ($users as $user) {
      $user->detachPermissions($user->permissions);
    }
  }
}
