<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

abstract class AbstractController
{
    protected function success($data, string $message, ?int $status = 200): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $message

        ], $status);
    }

    protected function error(string $message, ?int $status = 400): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message
        ], $status);
    }

    protected function listing($data, ?string $object = 'list', ?int $status = 200): JsonResponse
    {
        return response()->json([
            'object' => $object,
            'data' => $data
        ], $status);
    }

    protected function streamHeaders(): array
    {
        return [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ];
    }
}
