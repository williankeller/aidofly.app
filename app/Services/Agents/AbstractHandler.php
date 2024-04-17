<?php

namespace App\Services\Agents;

use App\Models\Preset;
use App\Models\Library;
use Illuminate\Support\Str;

abstract class AbstractHandler
{
    protected function storeLibrary(
        string $model,
        int $cost,
        array $params,
        string $content,
        ?Preset $preset = null
    ) {
        try {
            $library = Library::create([
                'model' => $model,
                'visibility' => 'public',
                'cost' => $cost,
                'params' => $params,
                'title' => Str::limit($params['prompt'], 125, '...'),
                'content' => $content,
                'user_id' => auth()->user()->id,
                'preset_id' => $preset?->id ?? null,
                'category_id' => $preset?->category_id ?? null,
            ]);
            return $library->makeHidden(['id', 'user_id', 'created_at', 'updated_at']);
        } catch (\Throwable $th) {

            logger()->error($th->getMessage());

            throw new \Exception('Failed to store library');
        }
    }
}
