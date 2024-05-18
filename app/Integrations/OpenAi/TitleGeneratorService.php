<?php

namespace App\Integrations\OpenAi;

use App\Services\Costs\CostCalculator;
use Gioni06\Gpt3Tokenizer\Gpt3Tokenizer;
use OpenAI\Client;
use Illuminate\Support\Collection;
use App\Services\Costs\ValueObjects\Count;

class TitleGeneratorService extends AbstractOpenAiService
{
    public function __construct(
        protected Client $client,
        protected Gpt3Tokenizer $tokenizer,
        protected CostCalculator $calc
    ) {
    }

    public function generateTitle(string $content, ?string $model = 'gpt-3.5-turbo'): Collection
    {
        $resp = $this->client->chat()->create([
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $this->completionContent($content),
                ],
            ]
        ]);

        $cost = $this->calculateCosts(
            $resp->usage->promptTokens,
            $resp->usage->completionTokens,
            $model
        );

        return $this->response($resp->choices[0]->message->content, $cost);
    }

    /**
     * @param string $title
     * @param Count $cost
     * @return Collection<TKey, TValue>
     */
    private function response(string $title, Count $cost): Collection
    {
        return collect([
            'title' => trim(trim($title), '"'),
            'cost' => $cost
        ]);
    }

    private function completionContent(string $content): string
    {
        // Get current Laravel app selected language
        $language = app()->getLocale();

        return 'Summarize the content delimited by triple quotes to be a title using the same language and with maximum of 120 characters. """' . $this->getWords($content) . '"""';
    }
}
