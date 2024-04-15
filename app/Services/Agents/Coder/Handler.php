<?php

namespace App\Services\Agents\Coder;

use App\Services\Stream\Streamer;
use App\Integrations\OpenAi\CompletionService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Generator;

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

        // The $title will be the prompt the user sent max of 255 characters
        $title = Str::limit($params['prompt'], 255);

        return [
            'object' => 'document',
            'id' => Str::uuid(),
            'model' => $model,
            'visibility' => 'public',
            'cost' => $cost,
            'params' => $params,
            'title' => $title,
            'content' => $content,
            'user' => [],
        ];
    }
}
