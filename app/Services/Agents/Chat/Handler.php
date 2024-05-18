<?php

namespace App\Services\Agents\Chat;

use App\Services\Agents\AbstractHandler;
use App\Services\Stream\Streamer;
use App\Services\Agents\Writer\Preset\TemplateParser;
use App\Integrations\OpenAi\ChatService;
use App\Integrations\OpenAi\TitleGeneratorService;
use App\Models\Library;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Generator;

/**
 * Handler class to manage the generation of text using AI models.
 */
class Handler extends AbstractHandler
{
    public function __construct(
        private Streamer $streamer,
        private ChatService $chatService,
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
    public function handle(string $model, array $params, ?string $uuid = null)
    {
        if (!$this->chatService->supportsModel($model)) {
            throw new ValidationException('Model not supported');
        }

        $history = $this->getChatHistory($params, $uuid);

        $completion = $this->chatService->generateChat($model, [
            'messages' => $history->get('messages'),
            'temperature' => $params['creativity'] ?? 1
        ]);

        return $this->processChatResponse($completion, $history, $model);
    }

    /**
     * Processes the AI-generated completion data.
     *
     * @param Generator $completion The response from the completion service.
     * @param Collection $preset Data including the prompt and preset instance (Model).
     * @param string $model The model name used in the process.
     * @return Generator
     * @throws \Exception If an error occurs during title generation or storing.
     */
    private function processChatResponse(Generator $completion, Collection $history, string $model): Generator
    {
        $messages = $history->get('messages') ?? [];

        $content = '';
        foreach ($completion as $token) {
            $content .= $token->value;
            yield $token;
        }

        try {
            $messages[] = [
                'role' => 'system',
                'content' => $content
            ];

            // Generate title only on the first message
            if ($history->get('instance') === null) {
                $completionTitle = $this->titleGeneratorService->generateTitle($content);

                $completionTitleCostValue = $completionTitle->get('cost')->getValue();
                $completionTitleTokensCount = $completionTitle->get('cost')->getTokens();
            } else {
                $completionTitleCostValue = $history->get('instance')->cost;
                $completionTitleTokensCount = $history->get('instance')->tokens;
            }

            $promptCostValue = $completion->getReturn()->getValue();
            $promptCost = $promptCostValue + $completionTitleCostValue;

            $promptTokensCount = $completion->getReturn()->getTokens();
            $promptTokens = $promptTokensCount + $completionTitleTokensCount;

            $costs = $this->chatService->count($promptCost, $promptTokens);

            if ($history->get('instance') === null) {
                return $this->storeLibrary(
                    type: 'chat',
                    model: $model,
                    title: $completionTitle->get('title'),
                    content: json_encode($messages),
                    cost: $costs->getValue(),
                    tokens: $costs->getTokens()
                );
            } else {
                $this->updateLibrary(
                    uuid: $history->get('instance')->uuid,
                    content: json_encode($messages),
                    cost: $costs->getValue(),
                    tokens: $costs->getTokens()
                );

                return $history->get('instance');
            }
        } catch (\Throwable $th) {
            logger()->error('[Writer] Error processing chat', ['exception' => $th]);
            throw new \Exception('Something went wrong. Please try again.');
        }
    }

    public function getChatHistory(array $params, ?string $uuid = null): Collection
    {
        $messages = [
            [
                'role' => 'user',
                'content' => $params['prompt']
            ]
        ];

        if (isset($params['reference']) && Str::isUuid($params['reference'])) {

            $library = Library::where('uuid', $params['reference'])
                ->where('user_id', auth()->user()->id)
                ->firstOrFail();

            // combine array library content to prompt
            $prompts = Json::decode($library->content);

            logger()->info('Library content', [$prompts]);

            array_unshift($messages, ...$prompts);
        }

        return collect([
            'messages' => $messages,
            'instance' => $library ?? null
        ]);
    }
}
