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

        $data = [
            'content' => $params['prompt'],
            'sistem' => "You are a {$params['language']} programming expert.",
            'prompt' => "You are a {$params['language']} programming expert. {$params['prompt']}",
        ];

        $resp = $this->completionService->generateCompletion($model, $data);

        // Log the $data when in dev mode   
        if (config('app.env') === 'local') {
            logger($data);
        }

        $content = '';
        foreach ($resp as $token) {
            $content .= $token->value;
            yield $token;
        }

        /** @var Count */
        $cost = $resp->getReturn();

        $title = Str::limit($params['prompt'], 125, '...');

        $library = Library::create([
            'type' => 'coder',
            'model' => $model,
            'visibility' => 'public',
            'cost' => $cost->jsonSerialize(),
            'params' => $params,
            'title' => $title,
            'content' => $content,
            'user_id' => auth()->user()->id,
        ]);

        return $library->makeHidden(['id', 'user_id', 'created_at', 'updated_at']);
    }
}
