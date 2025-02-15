<?php

namespace App\Services\Costs;

use App\Services\Costs\ValueObjects\Count;

class CostCalculator
{
    public const INPUT = 1;
    public const OUTPUT = 2;
    public const SIZE_256x256 = 4;
    public const SIZE_512x512 = 8;
    public const SIZE_1024x1024 = 16;
    public const SIZE_1024x1792 = 32;
    public const SIZE_1792x1024 = 64;
    public const QUALITY_SD = 128;
    public const QUALITY_HD = 256;

    private int $bitmask = 0;

    public function __construct(
        private array $rates = []
    ) {
        $this->calculateBitMask();
    }

    public function setRates(): void
    {
        $this->rates = [
            // 1 CREDIT = 16,000 TOKENS
            'gpt-3.5-turbo-input' => 0.0000625,  // 1 CREDIT = 16,000 TOKENS
            'gpt-3.5-turbo-output' => 0.0001875, // 1 CREDIT = 5.33K TOKENS

            'gpt-4-input' => 0.00375,            // 1 CREDIT = 266.67 TOKENS
            'gpt-4-output' => 0.0075,            // 1 CREDIT = 133.33 TOKENS

            'gpt-4-turbo-input' => 0.00125,      // 1 CREDIT = 800 TOKENS
            'gpt-4-turbo-output' => 0.00375,     // 1 CREDIT = 266.67 TOKENS

            'elevenlabs' => 0.020625,            // 1 CREDIT = 48.48 CHARACTERS

            'tts-1' => 0.001875,                 // 1 CREDIT = 533.33 CHARACTERS
            'tts-1-hd' => 0.00375,               // 1 CREDIT = 266.67 CHARACTERS
        ];
    }

    public function calculate(float|int $amount, $model, ?int $opt = null): Count
    {
        $this->setRates();

        if (isset($this->rates[$model])) {
            return new Count($amount * (float) $this->rates[$model]);
        }

        if (!is_null($opt) && !($this->bitmask & $opt)) {
            return new Count(0);
        }

        if ($opt & self::INPUT) {
            return new Count($amount * (float) $this->rates[$model . "-input"]);
        }

        if (isset($this->rates[$model . "-output"])) {
            return new Count($amount * (float) $this->rates[$model . "-output"]);
        }

        if (in_array(
            $model,
            [
                'eleven_multilingual_v2',
                'eleven_multilingual_v1',
                'eleven_monolingual_v1'
            ]
        )) {
            return new Count($amount * (float)($this->rates['elevenlabs'] ?? 0));
        }

        if ($model === 'dall-e-3') {
            $quality  = $opt & self::QUALITY_SD ? 'standard' : 'hd';
            $size = $opt & self::SIZE_1024x1024 ? '1024' : '1792';

            return new Count($amount * (float)($this->rates['dall-e-3-' . $quality . '-' . $size] ?? 0));
        }

        if ($model === 'dall-e-2') {
            $size = '1024';

            if ($opt & self::SIZE_512x512) {
                $size = '512';
            } elseif ($opt & self::SIZE_256x256) {
                $size = '256';
            }

            return new Count($amount * (float)($this->rates['dall-e-2-' . $size] ?? 0));
        }

        // Add a return statement here to ensure a Count object is returned in all cases
        return new Count(0);
    }

    private function calculateBitMask(): void
    {
        $this->bitmask =
            self::INPUT
            | self::OUTPUT
            | self::SIZE_256x256
            | self::SIZE_512x512
            | self::SIZE_1024x1024
            | self::SIZE_1024x1792
            | self::SIZE_1792x1024
            | self::QUALITY_SD
            | self::QUALITY_SD
            | self::QUALITY_HD;
    }
}
