<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
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

		if (!env("LDAP_AUTHENTICATION")) {
			$token = auth('api')->attempt(request(['username', 'password']));

			if ($token) {
				return $this->respondWithToken($token);
			}
		} else {
			$ldap = new Ldap();

			if ($ldap->connection && $ldap->verify_open_port()) {
				if ($ldap->bind($request['username'], $request['password'])) {
					$ldap->unbind();
					$user = User::where('username', $request['username'])->where('active', true)->first();
					if ($user) {
						if (!Hash::check($request['password'], $user->password)) {
							$user->password = Hash::make($request['password']);
							$user->remember_token = null;
							$user->save();
						}
						$token = auth('api')->login($user);
						$user->remember_token = $token;
						$user->save();
						return $this->respondWithToken($token);
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

	public function guard()
	{
		return Auth::Guard('api');
	}
}