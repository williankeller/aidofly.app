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
use Illuminate\Support\Collection;

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
            'prompt' => $preset->get('prompt'),
            'temperature' => $params['creativity'] ?? 1
        ]);

        return $this->processCompletion($resp, $params, $preset, $model);
    }

    /**
     * Processes the AI-generated completion data.
     *
     * @param $resp The response from the completion service.
     * @param array $params Parameters used in the process.
     * @param Collection $preset Data including the prompt and preset instance (Model).
     * @return Generator
     * @throws \Exception If an error occurs during title generation or storing.
     */
    private function processCompletion($resp, array $params, Collection $preset, string $model): Generator
    {
        $content = '';
        foreach ($resp as $token) {
            $content .= $token->value;
            yield $token;
        }

        try {
            $titleResp = $this->titleGeneratorService->generateTitle($content);
            $params['title'] = $titleResp->get('title') ?? null;

            $promptCost = $resp->getReturn()->getValue() + $titleResp->get('cost')->getValue();
            $promptTokens = $resp->getReturn()->getTokens() + $titleResp->get('cost')->getTokens();

            $costs = $this->completionService->count($promptCost, $promptTokens);

            return $this->storeLibrary(
                'writer',
                $model,
                $params,
                $content,
                $costs->getValue(),
                $costs->getTokens(),
                $preset->get('instance')?->id ?? null
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
     */
    public function getPresetPrompt(array $params, ?string $uuid = null): Collection
    {
        $prompt = $params['prompt'] ?? '';

        if ($uuid) {
            $preset = Preset::select(['id', 'uuid', 'template', 'category_id'])
                ->where('uuid', $uuid)
                ->where('status', 1)
                ->firstOrFail();

            $prompt = $this->parser->fillTemplate($preset->template, $params);
        }
        return collect([
            'prompt' => $prompt ?? '',
            'instance' => $preset ?? null
        ]);
    }
}
