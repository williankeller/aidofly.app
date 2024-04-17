<?php

namespace App\Services\Agents;

use App\Models\Library;
use Illuminate\Support\Str;

abstract class AbstractHandler
{
    protected function storeLibrary(
        string $type,
        string $model,
        int $cost,
        array $params,
        string $content
    ) {
        $library = Library::create([
            'type' => $type,
            'model' => $model,
            'visibility' => 'public',
            'cost' => $cost,
            'params' => $params,
            'title' => Str::limit($params['prompt'], 125, '...'),
            'content' => $content,
            'user_id' => auth()->user()->id,
        ]);

        return $library->makeHidden(['id', 'user_id', 'created_at', 'updated_at']);
    }
}
