<?php

namespace App\Services\Agents\Content;

use App\Services\Agents\AbstractHandler;
use App\Services\Stream\Streamer;
use App\Integrations\OpenAi\CompletionService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Generator;

class Handler extends AbstractHandler
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

        $resp = $this->completionService->generateCompletion($model, [
            'prompt' => $params['prompt'],
            'temperature' => $params['creativity'] ?? 1,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $params['prompt']
                ],
            ],
        ]);

        $content = '';
        foreach ($resp as $token) {
            $content .= $token->value;
            yield $token;
        }

        /** @var Count */
        $cost = $resp->getReturn();

        return $this->storeLibrary('content', $model, $cost->jsonSerialize(), $params, $content);
    }
}
