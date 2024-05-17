<?php

namespace App\Integrations\OpenAi;

use App\Services\Costs\CostCalculator;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use OpenAI\Client;
use Throwable;

class SpeechService
{
    const MODELS = [
        'tts-1',
        'tts-1-hd'
    ];

    public function __construct(
        private Client $client,
        private CostCalculator $calc
    ) {
    }

    public function supportsModel(string $model): bool
    {
        return in_array($model, self::MODELS);
    }

    public function generateSpeech(array $data = []): Collection
    {
        // Log the $data when in dev mode   
        if (config('app.env') === 'local') {
            logger($data);
        }

        if (!$data || !array_key_exists('input', $data)) {
            throw new Exception('Missing parameter: prompt');
        }

        try {
            $audioContent = $this->client->audio()->speech($data);
        } catch (Throwable $th) {
            throw new Exception(
                $th->getMessage(),
                $th->getCode(),
                $th
            );
        }

        $characters = Str::length($data['input']);

        return collect([
            'audioContent' => $audioContent,
            'cost' => $this->calc->calculate($characters, $data['model']),
            'characters' => $characters,
        ]);
    }
}
