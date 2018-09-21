<?php

namespace App\Http\Middleware;

use App\UserAction;
use Closure;
use Illuminate\Support\Facades\Log;

class UserActionMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$method = $request->method();

		if (($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('patch') || $request->isMethod('delete')) && !strpos($request->path(), 'user_action')) {
			$action = new UserAction();

			$data = $request->all();
			if (array_key_exists('password', $data)) {
				unset($data['password']);
			}
			if (is_array($data) && count($data) == 0) {
				$data = null;
			}
			$action->data = $data;

			if (auth('api')->user()) {
				$action->user_id = auth('api')->user()->id;
			} else {
				$action->user_id = null;
			}

			$action->path = $request->path();
			$action->method = $request->method();
			$action->data = json_encode($action->data);

			$action->save();
		}

		return $next($request);
	}
}
