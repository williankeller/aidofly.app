<?php

namespace App\Services\Agents;

use App\Models\Library;
use Illuminate\Support\Str;

abstract class AbstractHandler
{
    protected function storeLibrary(
        string $type,
        string $model,
        array $params,
        string $content,
        float $cost,
        ?int $tokens = null,
        ?int $resourceId = null
    ) {
        try {
            if (!isset($params['prompt'])) {
                // Get first element from array
                $params['prompt'] = reset($params);
            }

            $library = Library::create([
                'type' => $type,
                'model' => $model,
                'visibility' => 'private',
                'params' => $params,
                'title' =>  Str::limit($params['title'] ?? $params['prompt'], 125),
                'content' => $content,
                'cost' => $cost,
                'tokens' => $tokens,
                'user_id' => auth()->user()->id,
                'resource_id' => $resourceId
            ]);
            return $library->makeHidden(['id', 'user_id', 'created_at', 'updated_at']);
        } catch (\Throwable $th) {

            logger()->error('[Library]', [$th->getMessage(), $th]);

            throw new \Exception('Failed to save library');
        }
    }
}
