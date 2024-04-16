<?php

namespace App\Http\Controllers\Api\Agents;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AbstractController;
use App\Models\Preset;

class PresetController extends AbstractController
{
    public function handle()
    {
        $presets = Preset::with(['category' => fn ($query) => $query->select('id', 'title')])
            ->where('status', 1)
            ->get()
            ->makeHidden(['id', 'visibility', 'status', 'category_id', 'created_at', 'updated_at']);

        return $this->listing($presets);
    }
}
