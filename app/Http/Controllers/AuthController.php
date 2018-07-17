<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthForm;
use Illuminate\Support\Facades\Hash;
use App\User;

class AuthController extends Controller
{
  private $config;
  private $adldap;

  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login']]);

    $this->config = array(
      'user_id_key' => env("ADLDAP_ACCOUNT_PREFIX"),
      'auto_connect' => env("ADLDAP_AUTO_CONNECT"),
      'account_suffix' => env("ADLDAP_ACCOUNT_SUFFIX"),
      'base_dn' => env("ADLDAP_BASEDN"),
      'domain_controllers' => array(env("ADLDAP_CONTROLLERS")),
      'ad_port' => env("ADLDAP_PORT"),
      'use_ssl' => env("ADLDAP_USE_SSL"),
      'use_tls' => env("ADLDAP_USE_TLS"),
    );

    $this->adldap = new \Adldap\Adldap($this->config);
  }

  /**
   * Get a JWT via given credentials.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(AuthForm $request)
  {
    $credentials = request(['username', 'password']);

    $bind = $this->adldap->authenticate($this->config['user_id_key'].'='.$credentials['username'].',', $credentials['password']);

    if ($bind) {
      $user = User::where('username', $credentials['username'])->first();
      if ($user) {
        if (!Hash::check($credentials['password'], $user->password)) {
          $user->password = Hash::make($credentials['password']);
          $user->remember_token = null;
          $user->save();
        }
      } else {
        $user = new User();
        $user->username = $credentials['username'];
        $user->password = Hash::make($credentials['password']);
        $user->save();
      }
      $token = auth('api')->attempt($credentials);
      $user->remember_token = $token;
      $user->save();
    } else {
      return response()->json([
        'message' => 'No autorizado',
        'errors' => [
          'type' => ['Usuario o contraseña incorrectos'],
        ]
      ], 401);
    }

    return $this->respondWithToken($token);
  }

  /**
   * Get the authenticated User.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function me()
  {
    return response()->json(auth('api')->user());
  }

  /**
   * Log the user out (Invalidate the token).
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    auth('api')->logout();
    return response()->json(null, 201);
  }

  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh()
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
  protected function respondWithToken($token)
  {
    $this->guard()->user()->roles;

    return response()->json([
      'token' => $token,
      'token_type' => 'Bearer',
      'expires_in' => auth('api')->factory()->getTTL(),
      'user' => $this->guard()->user(),
      'message' => 'Indentidad verificada',
    ], 200);
  }

  public function guard() {
    return Auth::Guard('api');
  }
}