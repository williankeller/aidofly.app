<?php

namespace App\Services\Agents\Coder;

use App\Services\Stream\Streamer;
use App\Integrations\OpenAi\CompletionService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Generator;
use App\Models\Library;

class Handler
{
    public function __construct(
        private Streamer $streamer,
        private CompletionService $completionService
    ) {
    }

    public function handle(string $model, array $params): Generator
    {
        if (!$this->completionService->supportsModel($model)) {
            throw new ValidationException('Model not supported');
        }

        $resp = $this->completionService->generateCompletion($model, $params);

        $content = '';
        foreach ($resp as $token) {
            $content .= $token->value;
            yield $token;
        }

        /** @var Count */
        $cost = $resp->getReturn();

        $title = Str::limit($params['prompt'], 125, '...');

        $library = Library::create([
            'object' => 'document',
            'model' => $model,
            'visibility' => 'public',
            'cost' => $cost->jsonSerialize(),
            'params' => $params,
            'title' => $title,
            'content' => $content,
            'user_id' => 1 //<-- This is the user_id that should be set to the authenticated user, 
        ]);

        return $library->makeHidden(['id', 'user_id', 'created_at', 'updated_at']);
    }
}
