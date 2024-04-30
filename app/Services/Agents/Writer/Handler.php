<?php

namespace App\Services\Agents\Writer;

use App\Services\Agents\AbstractHandler;
use App\Services\Stream\Streamer;
use App\Models\Preset;
use App\Services\Agents\Writer\Preset\TemplateParser;
use App\Integrations\OpenAi\CompletionService;
use Illuminate\Validation\ValidationException;
use Generator;

class Handler extends AbstractHandler
{
    public function __construct(
        private Streamer $streamer,
        private CompletionService $completionService,
        private TemplateParser $parser
    ) {
    }

    public function handle(string $model, array $params, ?string $uuid = null): Generator
    {
        if (!$this->completionService->supportsModel($model)) {
            throw new ValidationException('Model not supported');
        }

        $preset = $this->getPresetPrompt($params, $uuid);

        $resp = $this->completionService->generateCompletion($model, [
            'prompt' => $preset['prompt'],
            'temperature' => $params['creativity'] ?? 1
        ]);

        $content = '';
        foreach ($resp as $token) {
            $content .= $token->value;
            yield $token;
        }

        /** @var Count */
        $cost = $resp->getReturn();

        return $this->storeLibrary(
            'writer',
            $model,
            $params,
            $content,
            $cost->jsonSerialize(),
            $cost->getTokens(),
            $preset['model']?->id ?? null
        );
    }

    public function getPresetPrompt(array $params, ?string $uuid = null): array
    {
        $prompt = $params['prompt'] ?? '';

        if ($uuid) {
            $preset = Preset::select(['id', 'uuid', 'template', 'category_id'])
                ->where('uuid', $uuid)
                ->where('status', 1)
                ->firstOrFail();

            $prompt = $this->parser->fillTemplate(
                $preset->template,
                $params
            );
        }
        return [
            'prompt' => $prompt,
            'model' => $preset ?? null
        ];
    }
}
