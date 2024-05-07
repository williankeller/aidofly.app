<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

abstract class AbstractController
{
    protected function error(string $message, ?int $status = 400): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'error' => $message
        ], $status);
    }

    protected function response($data, ?string $object = 'object', ?int $status = 200): JsonResponse
    {
        return response()->json([
            'object' => $object,
            'data' => $data
        ], $status);
    }

    protected function listing($data, int $page, int $limit, int $total): JsonResponse
    {
        return response()->json([
            'object' => 'list',
            'data' => $data,
            "pagination" => [
                "page" => $page,
                "pages" => ceil($total / $limit),
                "total" => $total,
            ],
        ], 200);
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
