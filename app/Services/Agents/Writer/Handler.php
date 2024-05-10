<?php

namespace App\Services\Agents\Writer;

use App\Services\Agents\AbstractHandler;
use App\Services\Stream\Streamer;
use App\Models\Preset;
use App\Services\Agents\Writer\Preset\TemplateParser;
use App\Integrations\OpenAi\CompletionService;
use App\Integrations\OpenAi\TitleGeneratorService;
use Illuminate\Validation\ValidationException;
use Generator;

/**
 * Handler class to manage the generation of text using AI models.
 */
class Handler extends AbstractHandler
{
    public function __construct(
        private Streamer $streamer,
        private CompletionService $completionService,
        private TitleGeneratorService $titleGeneratorService,
        private TemplateParser $parser
    ) {
    }

    /**
     * Handles the generation process by orchestrating calls to various services.
     *
     * @param string $model Model identifier.
     * @param array $params Parameters for the generation process.
     * @param string|null $uuid Optional UUID for preset retrieval.
     * @return Generator
     * @throws ModelNotSupportedException If the model is not supported.
     * @throws \Exception If an error occurs during the generation or storing process.
     */
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

        return $this->processCompletion($resp, $params, $preset, $model);
    }

    /**
     * Processes the AI-generated completion data.
     *
     * @param $resp The response from the completion service.
     * @param array $params Parameters used in the process.
     * @param array $preset Data including the prompt and model.
     * @return Generator
     * @throws \Exception If an error occurs during title generation or storing.
     */
    private function processCompletion($resp, array $params, array $preset, string $model): Generator
    {
        $content = '';
        foreach ($resp as $token) {
            $content .= $token->value;
            yield $token;
        }

        try {
            $titleResp = $this->titleGeneratorService->generateTitle($content);
            $params['title'] = $titleResp->title ?? null;

            $promptCost = $resp->getReturn()->value + $titleResp->cost->value;
            $promptTokens = $resp->getReturn()->getTokens() + $titleResp->cost->getTokens();

            $costs = $this->completionService->count($promptCost, $promptTokens);

            return $this->storeLibrary(
                'writer',
                $model,
                $params,
                $content,
                $costs->jsonSerialize(),
                $costs->getTokens(),
                $preset['model']?->id ?? null
            );
        } catch (\Throwable $th) {
            logger()->error('[Writer]', ['Error processing completion: ' . $th->getMessage(), $th]);
            throw new \Exception('Something went wrong. Please try again.');
        }
    }

    /**
     * Retrieves or generates a prompt using preset data if available.
     *
     * @param array $params Parameters for prompt generation.
     * @param string|null $uuid Optional UUID for preset retrieval.
     * @return array
     */
    public function getPresetPrompt(array $params, ?string $uuid = null): array
    {
        $prompt = $params['prompt'] ?? '';

        if ($uuid) {
            $preset = Preset::select(['id', 'uuid', 'template', 'category_id'])
                ->where('uuid', $uuid)
                ->where('status', 1)
                ->firstOrFail();

            $prompt = $this->parser->fillTemplate($preset->template, $params);
        }
        return [
            'prompt' => $prompt ?? '',
            'model' => $preset ?? null
        ];
    }
}
