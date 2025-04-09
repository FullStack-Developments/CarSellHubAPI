<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    public function sendSuccess($data, $message, $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => $message,
        ], $code);
    }
    public function sendError($message, $code = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'data' => [],
            'message' => $message,
        ], $code);
    }

    public function sendValidationError($message, $code = 422): JsonResponse
    {
        return response()->json([
            'success' => 'error',
            'data' => [],
            'message' => $message,
        ], $code);
    }
}
