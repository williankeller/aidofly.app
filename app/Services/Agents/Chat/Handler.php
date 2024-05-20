<?php

namespace App\Services\Agents\Chat;

use App\Services\Agents\AbstractHandler;
use App\Services\Stream\Streamer;
use App\Integrations\OpenAi\ChatService;
use App\Integrations\OpenAi\TitleGeneratorService;
use App\Services\Costs\ValueObjects\Count;
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
    ) {
    }

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

    private function calculateCosts(Generator $completion, ?Library $instance = null, ?Collection $title = null): Count
    {
        if (!$instance && $title) {
            $titleCost = $title->get('cost')->getValue();
            $titleTokens = $title->get('cost')->getTokens();
        } else {
            $titleCost = $instance->cost;
            $titleTokens = $instance->tokens;
        }

        $promptCost = $completion->getReturn()->getValue() + $titleCost;
        $promptTokens = $completion->getReturn()->getTokens() + $titleTokens;

        return $this->chatService->count($promptCost, $promptTokens);
    }

    private function persistChat(
        string $model,
        array $messages,
        Count $costs,
        ?Library $instance = null,
        ?Collection $title = null
    ) {
        $contentJson = Json::encode($messages);

        if (!$instance) {
            return $this->storeLibrary(
                type: 'chat',
                model: $model,
                title: $title->get('title'),
                content: $contentJson,
                cost: $costs->getValue(),
                tokens: $costs->getTokens(),
                params: []
            );
        } else {
            $this->updateLibrary(
                uuid: $instance->uuid,
                content: $contentJson,
                cost: $costs->getValue(),
                tokens: $costs->getTokens()
            );
            return $instance->makeHidden(['content']);
        }
    }

    protected function processChatResponse(Generator $completion, Collection $history, string $model)
    {
        $messages = $history->get('messages') ?? [];
        /** @var Library|null $instance */
        $instance = $history->get('instance');

        $content = '';
        foreach ($completion as $token) {
            $content .= $token->value;
            yield $token;
        }

        $messages[] = [
            'role' => 'system',
            'content' => $content
        ];

        $title = null;
        if ($instance === null) {
            $title = $this->titleGeneratorService->generateTitle($content);
        }
        $costs = $this->calculateCosts($completion, $instance, $title);

        return $this->persistChat($model, $messages, $costs, $instance, $title);
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

            $library = Library::select(['uuid', 'title', 'model', 'cost', 'tokens', 'content'])
                ->where('uuid', $params['reference'])
                ->where('user_id', auth()->id())
                ->first();

            $prompts = Json::decode($library->content);

            array_unshift($messages, ...$prompts);
        }

        return collect([
            'messages' => $messages,
            'instance' => $library ?? null
        ]);
    }
}
