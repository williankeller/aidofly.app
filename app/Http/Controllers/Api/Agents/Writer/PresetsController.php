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
            ->get()
            ->makeHidden(['id', 'visibility', 'template', 'category_id', 'user_id', 'created_at', 'updated_at']);

        return $this->listing($presets);
    }

    public function user()
    {
        $presets = Preset::with(['category' => fn ($query) => $query->select('id', 'title')])
            ->where('user_id', auth()->id())
            ->where('source', 'user')
            ->get()
            ->makeHidden(['id', 'visibility', 'template', 'category_id', 'user_id', 'created_at', 'updated_at']);

        return $this->listing($presets);
    }

    public function discover()
    {
        $presets = Preset::with(['category' => fn ($query) => $query->select('id', 'title')])
            ->where('status', true)
            ->where('visibility', 'public')
            ->where('source', 'user')
            ->where('user_id', '!=', auth()->id())
            ->get()
            ->makeHidden(['id', 'visibility', 'template', 'user_id', 'category_id', 'created_at', 'updated_at']);

        return $this->listing($presets);
    }
}
