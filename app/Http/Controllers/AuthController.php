<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
Use App\Helpers\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login']]);
  }

  /**
   * Get a JWT via given credentials.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'username' => 'required|min:5|max:255',
      'password' => 'required|min:6|max:255',
    ], [
      'username.required' => 'El campo de usuario no puede estar vacío',
      'username.min' => 'El número mínimo de caracteres es 5',
      'max' => 'El número máximo de caracteres es 255',
      'password.required' => 'El campo de contraseña no puede estar vacío',
      'password.min' => 'El número mínimo de caracteres es 6',
    ]);

    if ($validator->fails()) {
      return JsonResponse::response($validator->errors(), 'Solicitud inválida', null, 401);
    }

    $credentials = request(['username', 'password']);

    if (!$token = auth('api')->attempt($credentials)) {
      return JsonResponse::response(null, 'No autorizado', null, 401);
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
    return JsonResponse::response(auth('api')->user(), 'Token válida', null, 201);
  }

  /**
   * Log the user out (Invalidate the token).
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    auth('api')->logout();
    return JsonResponse::response(null, 'Sesión terminada', null, 201);
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
    return JsonResponse::response([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth('api')->factory()->getTTL(),
    ], 'Indentidad verificada', null, 200);
  }
}