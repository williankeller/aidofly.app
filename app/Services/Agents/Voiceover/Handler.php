<?php

namespace App\Services\Agents\Voiceover;

use App\Services\Agents\AbstractHandler;
use App\Services\Stream\Streamer;
use App\Integrations\OpenAi\SpeechService;
use App\Models\Library;
use App\Models\Voice;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Handler extends AbstractHandler
{
    public function __construct(
        private Streamer $streamer,
        private SpeechService $speechService
    ) {
    }

    public function handle(string $uuid, $params)
    {
        $voice = Voice::where('uuid', $uuid)->where('status', true)->firstOrFail();

        if (!$this->speechService->supportsModel($voice->model)) {
            throw new ValidationException('Model not supported');
        }

        $voiceover = $this->speechService->generateSpeech([
            'model' => $voice->model,
            'input' => $params['prompt'],
            'voice' => $voice->token
        ]);

        $filename = $voice->uuid . '/' . Str::uuid() . '.mp3';
        $path = 'voiceover/' . $filename;

        try {
            $this->storeLibrary(
                'voiceover',
                $voice->model,
                $params,
                $filename,
                $voiceover['cost']->jsonSerialize(),
                $voice->id
            );

            // Saving the audio file to Laravel's storage
            // You can choose a different naming convention
            Storage::disk('local')->put($path, $voiceover['audioContent']);

            return [
                'content' => $filename
            ];
        } catch (\Exception $e) {
            throw new \Exception('Failed to rewind the stream');
        }
    }
}
