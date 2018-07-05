<?php

namespace App\Helpers;

class JsonResponse
{
  public static function response($data, $messageSuccess, $messageNotFound)
  {
    if ($data) {
      return response()->json([
        'code' => 200,
        'error' => false,
        'message' => $messageSuccess,
        'data' => $data,
        ], 200);
    } else {
      return response()->json([
        'code' => 404,
        'error' => true,
        'message' => $messageNotFound,
        'data' => null,
      ], 404);
    }
  }
}