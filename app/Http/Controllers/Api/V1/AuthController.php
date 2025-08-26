<?php

namespace App\Http\Controllers\Api\V1;

use JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Employee;
use App\UserAction;
use Ldap;

/** @resource Authenticate
 *
 * Resource to authenticate via username/password credentials
 */

class AuthController extends Controller
{
  /**
   * Get a JWT via given credentials.
   *
   * Login, return a JsonWebToken to request as "Bearer" Authorization header
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(AuthForm $request)
  {
    if ($request['username'] == 'admin') {
      $token = auth('api')->attempt(request(['username', 'password']));

      if ($token) {
        return $this->respondWithToken($token);
      }
    }

    $user = User::whereUsername($request['username'])->first();
    if ($user) {
      if (!$user->active) {
        return response()->json([
          'message' => 'No autorizado',
          'errors' => [
            'type' => ['Usuario desactivado'],
          ],
        ], 401);
      }
    }

    if (!env("LDAP_AUTHENTICATION")) {
      $token = auth('api')->attempt(request(['username', 'password']));

      if ($token) {
        return $this->respondWithToken($token);
      }
    } else {
      $ldap = new Ldap();

      if ($ldap->connection && $ldap->verify_open_port()) {
        if ($ldap->bind($request['username'], $request['password'])) {
          $user = User::where('username', $request['username'])->where('active', true)->first();
          if ($user) {
            if (!Hash::check($request['password'], $user->password)) {
              $user->password = Hash::make($request['password']);
              $user->save();
            }
            $token = auth('api')->login($user);
            $ldap->unbind();
            return $this->respondWithToken($token);
          } else {
            $employee = Employee::find($ldap->get_entry($request['username'], 'uid')['employeeNumber']);
            $employee->username = $request['username'];
            $token = JWTAuth::fromUser(new User(['username' => $request['username']]));
            $ldap->unbind();
            return $this->respondWithToken($token, $employee);
          }
        }
        return response()->json([
          'message' => 'No autorizado',
          'errors' => [
            'type' => ['Usuario o contraseña incorrectos'],
          ],
        ], 401);
      }
    }

    return response()->json([
      'message' => 'No autorizado',
      'errors' => [
        'type' => ['Usuario o contraseña incorrectos'],
      ],
    ], 401);
  }

  /**
   * Get the authenticated User.
   *
   * Login, return a JsonWebToken to request as "Bearer" Authorization header
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function show()
  {
    return response()->json(auth('api')->user());
  }

  /**
   * Log the user out (Invalidate the token).
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy()
  {
    auth('api')->logout();
    return response()->json([
      'message' => 'Logged out successfully',
    ], 201);
  }

  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function update()
  {
    return $this->respondWithToken(auth('api')->refresh());
  }

  /**
   * Get the token array structure.
   *
   * @param  string $token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithToken($token, $employee = null)
  {
    $consultant = null;
    if ($employee == null) {
      $user = $this->guard()->user();
      $id = $user->employee_id;
      $username = $user->username;
      $role = $user->roles[0]->name;
      $permissions = array_unique(array_merge($user->roles[0]->permissions->pluck('name')->toArray(), $user->permissions->pluck('name')->toArray()));
    } else {
      $user = null;
      $id = $employee->id;
      $username = $employee->username;
      $role = 'guest';
      $permissions = [];
      $consultant = $employee->consultant();
    }

    $ip = request()->ip();

    if ($user) {
      UserAction::create([
        'user_id' => $user->id,
        'action' => "Usuario $username autenticado desde la dirección $ip"
      ]);
    }

    return response()->json([
      'token' => $token,
      'token_type' => 'Bearer',
      'expires_in' => auth('api')->factory()->getTTL(),
      'id' => $id,
      'user' => $username,
      'role' => $role,
      'permissions' => $permissions,
      'consultant' => $consultant,
      'message' => 'Identidad verificada',
    ], 200);
  }

  public function guard()
  {
    return Auth::Guard('api');
  }
}
