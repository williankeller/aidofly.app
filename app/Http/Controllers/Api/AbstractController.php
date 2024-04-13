<?php

namespace App\Http\Controllers\Api;

abstract class AbstractController
{
    protected function success($data = null, $message = null, $status = 200)
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $message

        ], $status);
    }

    protected function error($message = null, $status = 400)
    {
        return response()->json([
            'status' => $status,
            'message' => $message
        ], $status);
    }
}
