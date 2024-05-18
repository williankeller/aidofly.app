<?php

namespace App\Services\Agents;

use App\Models\Library;
use Illuminate\Support\Str;

abstract class AbstractHandler
{
    /**
     * Store the generated content in the library.
     * 
     * @param string $type (voiceover|writer|speech)
     * @param string $model Integration model (e.g. gpt-3.5-turbo)
     * @param array $params Parameters used in the generation process
     * @param string $content Generated content
     * @param float $cost Cost of the generation process
     * @param int|null $tokens Number of tokens (or chars) used in the generation process
     * @param int|null $resourceId Resource ID (agent) used in the generation process
     * @return Library
     * @throws \Exception
     */
    protected function storeLibrary(
        string $type,
        string $model,
        string $title,
        array $params,
        string $content,
        float $cost,
        ?int $tokens = null,
        ?int $resourceId = null
    ) {
        try {
            $library = Library::create([
                'type' => $type,
                'model' => $model,
                'visibility' => 'private',
                'params' => $params,
                'title' =>  Str::limit($title, 125),
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

    protected function updateLibrary(string $uuid, string $content, float $cost, ?int $tokens = null)
    {
        try {
            return Library::where('uuid', $uuid)
                ->where('user_id', auth()->user()->id)
                ->update([
                    'content' => $content,
                    'cost' => $cost,
                    'tokens' => $tokens,
                ]);
        } catch (\Throwable $th) {
            logger()->error('[Library]', [$th->getMessage(), $th]);
            throw new \Exception('Failed to update library');
        }
    }
}
