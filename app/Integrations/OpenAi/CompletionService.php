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

class CompletionService
{
    private array $models = [
        'gpt-4-turbo',
        'gpt-4',
        'gpt-3.5-turbo',
        'gpt-3.5-turbo-input',
        'gpt-3.5-turbo-instruct',
    ];

    public function __construct(
        private Client $client,
        private Gpt3Tokenizer $tokenizer,
        private CostCalculator $calc
    ) {
    }

    public function supportsModel(string $model): bool
    {
        return in_array($model, $this->models);
    }

    /**
     * @throws RuntimeException
     */
    public function generateCompletion(string $model, array $params = []): Generator
    {
        try {
            if ($model == 'gpt-3.5-turbo-instruct') {
                return $this->generateInstructedCompletion($model, $params);
            }
        } catch (ErrorException $th) {
            throw new Exception($th->getMessage(), previous: $th);
        }

        try {
            return $this->generateChatCompletion($model, $params);
        } catch (ErrorException $th) {
            throw new Exception($th->getMessage(), previous: $th);
        }
    }

    /**
     * @return Generator<int,Token,null,Count>
     * @throws ErrorException
     * @throws RuntimeException
     */
    private function generateInstructedCompletion(
        string $model,
        array $params = []
    ): Generator {
        $prompt = $params['prompt'] ?? '';

        $resp = $this->client->completions()->createStreamed([
            'model' => $model,
            'prompt' => $prompt,
            'temperature' => (int)($params['temperature'] ?? 1),
        ]);

        $inputTokensCount = $this->tokenizer->count($prompt);
        $outputTokensCount = 0;

        foreach ($resp as $item) {
            $content = $item->choices[0]->text;

            if ($content) {
                $outputTokensCount++;
                yield new Token($content);
            }
        }

        $inputCost = $this->calc->calculate(
            $inputTokensCount,
            $model,
            CostCalculator::INPUT
        );

        $outputCost = $this->calc->calculate(
            $outputTokensCount,
            $model,
            CostCalculator::OUTPUT
        );

        return new Count($inputCost->value + $outputCost->value);
    }

    /**
     * @return Generator<int,Token,null,Count>
     * @throws RuntimeException
     * @throws ErrorException
     */
    private function generateChatCompletion(
        string $model,
        array $params = []
    ): Generator {
        $prompt = $params['prompt'] ?? '';

        $resp = $this->client->chat()->createStreamed([
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ],
            ],
            'temperature' => (int)($params['temperature'] ?? 1),
        ]);

        $inputTokensCount = $this->tokenizer->count($prompt);
        $outputTokensCount = 0;

        foreach ($resp as $item) {
            $content = $item->choices[0]->delta->content;

            if ($content) {
                $outputTokensCount++;
                yield new Token($content);
            }
        }

        $inputCost = $this->calc->calculate(
            $inputTokensCount,
            $model,
            CostCalculator::INPUT
        );

        $outputCost = $this->calc->calculate(
            $outputTokensCount,
            $model,
            CostCalculator::OUTPUT
        );

        return new Count($inputCost->value + $outputCost->value);
    }
}
