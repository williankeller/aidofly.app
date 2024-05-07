<?php

namespace App\Integrations\OpenAi;

use App\Services\Costs\CostCalculator;
use App\Services\Costs\ValueObjects\Count;
use Illuminate\Support\Str;

abstract class AbstractOpenAiService
{
    public function __construct(
        protected CostCalculator $calc
    ) {
    }

    const MODELS = [
        'gpt-4',
        'gpt-4-turbo',
        'gpt-3.5-turbo',
        'gpt-3.5-turbo-input',
        'gpt-3.5-turbo-instruct',
    ];

    public function supportsModel(string $model): bool
    {
        return in_array($model, self::MODELS);
    }

    protected function getWords(string $content, int $count = 128): string
    {
        return Str::words($content, $count);
    }

    protected function calculateCosts(int $inputTokens, int $outputTokens, string $model): Count
    {
        $inputCost = $this->calc->calculate(
            $inputTokens ?? 0,
            $model,
            CostCalculator::INPUT
        );

        $outputCost = $this->calc->calculate(
            $outputTokens ?? 0,
            $model,
            CostCalculator::OUTPUT
        );

        $costTotal = $inputCost->value + $outputCost->value;
        $tokenTotal = $inputTokens + $outputTokens;

        return $this->count($costTotal, $tokenTotal);
    }

    public function count($cost, ?int $tokens = null): Count
    {
        return new Count($cost, $tokens);
    }

    /**
     * @return int|float
     */
    protected function convertTemperature(string $temperature)
    {
        $floatVal = floatval($temperature);

        if ((int) $floatVal == $floatVal) {
            return (int) $floatVal;
        } else {
            return $floatVal;
        }
    }

    public function logger(
        array $data,
        int $inputTokensCount,
        int $outputTokensCount,
    ): void {
        if (!config('app.debug')) {
            return;
        }

        logger()->info('[OpenAI Completion]', [
            'data' => $data,
            'tokens' => [
                'input' => $inputTokensCount,
                'output' => $outputTokensCount,
                'total' => $inputTokensCount + $outputTokensCount,
            ]
        ]);
    }
}
