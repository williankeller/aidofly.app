<?php

namespace App\Http\Controllers\Api\Agents;

use Generator;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AbstractController;
use App\Services\Stream\Streamer;
use App\Services\Agents\Coder\Handler;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CoderController extends AbstractController
{
    public function __construct(
        private Streamer $streamer,
        private Handler $handler
    ) {
    }

    public function handle(Request $request): StreamedResponse
    {
        $params = [
            'prompt' => $request->input('prompt'),
            'language' => $request->input('language'),
        ];

        $generator = $this->handler->handle('gpt-3.5-turbo', $params);

        return response()->stream(
            $this->callback($generator),
            200,
            $this->headers()
        );
    }

    private function headers(): array
    {
        return [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ];
    }

    private function callback(Generator $generator)
    {
        return function () use ($generator) {
            $this->streamer->stream($generator);

            $document = $generator->getReturn();

            $this->streamer->sendEvent('document', $document);

            $this->streamer->close();
        };
    }
}
