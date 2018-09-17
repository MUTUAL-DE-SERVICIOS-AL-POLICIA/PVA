<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		//
	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	/**
	 * Report or log an exception.
	 *
	 * @param  \Exception  $exception
	 * @return void
	 */
	public function report(Exception $exception)
	{
		parent::report($exception);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $exception
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $exception)
	{
		if ($exception instanceof ModelNotFoundException or $exception instanceof NotFoundHttpException) {
			return response()->json([
				'message' => 'Data not found',
				'errors' => [
					'type' => ['Recurso no encontrado'],
				]
			], 404);
		} elseif ($exception instanceof \PDOException) {
			$db_code = trim($exception->getCode());
			$code = 400;
			LOG::error('PDOException: ' . $db_code);
			switch (intval($db_code)) {
				case 23505:
					$error_message = 'Solicitud inválida';
					break;
				case 7:
					$error_message = 'Error en la conexión con el servidor';
					$code = 503;
					break;
				default:
					$error_message = 'Error en la base de datos';
					$code = 500;
			}
			return response()->json([
				'message' => 'Database error',
				'errors' => [
					'type' => [$error_message],
				]
			], $code);
		} elseif ($exception instanceof HttpException && $exception->getStatusCode() == 403) {
			return response()->json([
				'message' => 'Forbidden',
				'errors' => [
					'type' => ['No autorizado'],
				]
			], 403);
		} else {
			LOG::error('Error inesperado: ' . $exception);
		}
		return parent::render($request, $exception);
	}
}
