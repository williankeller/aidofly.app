<?php

namespace App\Integrations\OpenAi;

use App\Services\Stream\Domain\Token;
use App\Services\Costs\CostCalculator;
use App\Services\Costs\ValueObjects\Count;
use Exception;
use Generator;
use Gioni06\Gpt3Tokenizer\Gpt3Tokenizer;
use OpenAI\Client;
use OpenAI\Exceptions\ErrorException;
use RuntimeException;

class ChatService extends AbstractOpenAiService
{
    public function __construct(
        protected Client $client,
        protected Gpt3Tokenizer $tokenizer,
        protected CostCalculator $calc
    ) {
    }

    /**
     * @throws RuntimeException
     */
    public function generateChat(string $model, array $data = []): Generator
    {
        try {
            return $this->generateChatCompletion($model, $data);
        } catch (ErrorException $th) {
            throw new Exception($th->getMessage(), previous: $th);
        }
    }

    /**
     * @return Generator<int,Token,null,Count>
     * @throws RuntimeException
     * @throws ErrorException
     */
    private function generateChatCompletion(string $model, array $data = []): Generator
    {
        $resp = $this->client->chat()->createStreamed([
            'model' => $model,
            'messages' => $data['messages'],
            'temperature' => $this->convertTemperature($data['temperature']),
        ]);

        // Get the last user message from the $data['messages'] array
        $lastMessage = end($data['messages']);
        // Using the tokenizer to count the tokens in the prompt as the Streamed API does not return usage data
        $inputTokensCount = $this->tokenizer->count($lastMessage['content']);
        $outputTokensCount = 0;

        foreach ($resp as $item) {
            $content = $item->choices[0]->delta->content;

            if ($content) {
                $outputTokensCount++;
                yield new Token($content);
            }
        }

        $this->logger($data, $inputTokensCount, $outputTokensCount);

        return $this->calculateCosts($inputTokensCount, $outputTokensCount, $model);
    }
}
