<?php

namespace App\Helpers;

class JsonResponse
{
  public static function response($data, $messageSuccess, $messageNotFound, $code)
  {
    if (!$code) {
      if ($data) {
        return response()->json([
          'error' => false,
          'message' => $messageSuccess,
          'data' => $data,
        ], 200);
      } else {
        return response()->json([
          'error' => true,
          'message' => $messageNotFound,
          'data' => null,
        ], 404);
      }
    } else {
      return response()->json([
        'error' => ($code >= 400) ? true : false,
        'message' => $messageSuccess,
        'data' => $data,
      ], $code);
    }
  }
}