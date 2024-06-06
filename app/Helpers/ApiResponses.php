<?php

namespace App\Helpers;

class ApiResponses
{
    public static function ok($message, $data = [])
    {
        return self::success($message, $data, 200);
    }

    public static function success($message, $data, $statusCode = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    public static function error($errors = [], $statusCode = null)
    {
        if (is_string($errors)) {
            return response()->json([
                'message' => $errors,
                'status' => $statusCode
            ], $statusCode);
        }

        return response()->json([
            'message' => $errors
        ]);
    }

    public static function notAuthorized(string $message)
    {
        return response()->json([
            'status' => 403,
            'message' => $message
        ]);
    }
}
