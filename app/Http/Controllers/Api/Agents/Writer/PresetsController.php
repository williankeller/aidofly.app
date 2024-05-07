<?php

namespace App\Http\Controllers\Api\Agents\Writer;

use App\Http\Controllers\Api\AbstractController;
use App\Models\Preset;

class PresetsController extends AbstractController
{
    /**
     * Get the list of public system presets
     * @return \Illuminate\Http\JsonResponse
     * @see \App\Http\Controllers\Agents\Writer\WriterController::index
     */
    public function index()
    {
        $presets = Preset::with(['category' => fn ($query) => $query->select('id', 'title')])
            ->where('source', 'system')
            ->where('visibility', 'public')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->makeHidden(['id', 'visibility', 'template', 'category_id', 'user_id', 'created_at', 'updated_at']);

        return $this->listing($presets, 1, 100, $presets->count());
    }

    public function user()
    {
        $presets = Preset::with(['category' => fn ($query) => $query->select('id', 'title')])
            ->where('user_id', auth()->id())
            ->where('source', 'user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->makeHidden(['id', 'visibility', 'template', 'category_id', 'user_id', 'created_at', 'updated_at']);

        return $this->listing($presets, 1, 100, $presets->count());
    }

    public function discover()
    {
        $presets = Preset::with(['category' => fn ($query) => $query->select('id', 'title')])
            ->where('status', true)
            ->where('visibility', 'public')
            ->where('source', 'user')
            ->where('user_id', '!=', auth()->id())
            ->orderBy('usage_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->makeHidden(['id', 'visibility', 'template', 'user_id', 'category_id', 'created_at', 'updated_at']);

        return $this->listing($presets, 1, 100, $presets->count());
    }
}
