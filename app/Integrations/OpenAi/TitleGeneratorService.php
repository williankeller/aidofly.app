<?php

namespace App\Integrations\OpenAi;

use App\Services\Costs\CostCalculator;
use Gioni06\Gpt3Tokenizer\Gpt3Tokenizer;
use OpenAI\Client;

class TitleGeneratorService extends AbstractOpenAiService
{
    public function __construct(
        protected Client $client,
        protected Gpt3Tokenizer $tokenizer,
        protected CostCalculator $calc
    ) {
    }

    public function generateTitle(string $content, ?string $model = 'gpt-3.5-turbo')
    {
        if ($model == 'gpt-3.5-turbo-instruct') {
            return $this->generateInstructedCompletion($model, $content);
        }

        return $this->generateChatCompletion($model, $content);
    }

    private function generateInstructedCompletion(string $model, string $content)
    {
        $resp = $this->client->completions()->create([
            'model' => $model,
            'prompt' => $this->completionContent($content),
        ]);

        $cost = $this->calculateCosts(
            $resp->usage->promptTokens,
            $resp->usage->completionTokens,
            $model
        );

        return $this->response($resp->choices[0]->text, $cost, true);
    }

    /**
     * @param string $model
     * @param string $content
     * @return object<string, mixed>
     */
    private function generateChatCompletion(string $model, string $content): object
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
     * @param int|float $cost
     * @param bool $trimTitle
     * @return object<string, mixed>
     */
    private function response(string $title, $cost, $trimTitle = false): object
    {
        return (object) [
            'title' => !$trimTitle ? $title : trim(trim($title), '"'),
            'cost' => $cost
        ];
    }

    private function completionContent(string $content): string
    {
        // Get current Laravel app selected language
        $language = app()->getLocale();

        return 'Summarize the content delimited by triple quotes to be a title using the same language and with maximum of 120 characters. """' . $this->getWords($content) . '"""';
    }
}
