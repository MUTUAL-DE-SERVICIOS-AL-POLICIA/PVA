<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthForm;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/** @resource Authenticate
 *
 * Resource to authenticate via username/password credentials
 */

class AuthController extends Controller
{
	private $config;
	private $adldap;

	public function __construct()
	{
		if (config('app.debug')) {
			$this->middleware('auth:api', ['except' => ['store', 'show', 'update', 'destroy']]);
		} else {
			$this->middleware('auth:api', ['except' => ['store']]);
		}

		if (env("ADLDAP_AUTHENTICATION")) {
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
	}

	/**
	 * Get a JWT via given credentials.
	 *
	 * Login, return a JsonWebToken to request as "Bearer" Authorization header
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(AuthForm $request)
	{
		if (config('app.debug')) {
			$token = auth('api')->attempt([
				'username' => 'admin',
				'password' => 'admin',
			]);

			if ($token) {
				return $this->respondWithToken($token);
			}
		}

		$credentials = request(['username', 'password']);

		if (!env("ADLDAP_AUTHENTICATION")) {
			$token = auth('api')->attempt($credentials);

			if ($token) {
				return $this->respondWithToken($token);
			}
		} elseif (env("ADLDAP_AUTHENTICATION")) {
			$bind = $this->adldap->authenticate($this->config['user_id_key'] . '=' . $credentials['username'] . ',', $credentials['password']);

			if ($bind) {
				$user = User::where('username', $credentials['username'])->where('active', true)->first();
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

	/**
	 * Get the authenticated User.
	 *
	 * Login, return a JsonWebToken to request as "Bearer" Authorization header
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show()
	{
		if (config('app.debug')) {
			$token = auth('api')->attempt([
				'username' => 'admin',
				'password' => 'admin',
			]);

			if ($token) {
				return response()->json(auth('api')->user());
			}
		}
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
		if (config('app.debug')) {
			$token = auth('api')->attempt([
				'username' => 'admin',
				'password' => 'admin',
			]);

			if ($token) {
				return $this->respondWithToken($token);
			}
		}
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