<?php

namespace App\Http\Controllers\Api\Agents\Voiceover;

use App\Http\Controllers\Api\AbstractController;
use App\Models\Voice;
use Illuminate\Http\JsonResponse;

class VoicesController extends AbstractController
{
    public function index(): JsonResponse
    {
        $presets = Voice::where('status', 1)
            ->get()
            ->makeHidden(['id']);

        return $this->listing($presets, 1, 100, $presets->count());
    }
}
