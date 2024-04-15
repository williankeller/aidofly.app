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

        return [
            'object' => 'document',
            'id' => Str::uuid(),
            'model' => 'gpt-3.5-turbo',
            'visibility' => 'public',
            'cost' => .4,
            'created_at' => date('Y-m-d\TH:i:s\Z'),
            'params' => $params,
            'title' => 'Hello, I\'m your assistant. How can I help you today?',
            'content' => 'fasdfsdf',
            'user' => [],
        ];
    }
}
