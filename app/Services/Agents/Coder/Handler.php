<?php

namespace App\Services\Agents\Coder;

use App\Services\Agents\AbstractHandler;
use App\Services\Stream\Streamer;
use App\Integrations\OpenAi\CompletionService;
use Illuminate\Validation\ValidationException;
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
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are a {$params['language']} programming language expert."
                ],
                [
                    'role' => 'user',
                    'content' => $params['prompt']
                ],
            ]
        ]);

        $content = '';
        foreach ($resp as $token) {
            $content .= $token->value;
            yield $token;
        }

        /** @var Count */
        $cost = $resp->getReturn();

        return $this->storeLibrary('coder', $model, $cost->jsonSerialize(), $params, $content);
    }
}
