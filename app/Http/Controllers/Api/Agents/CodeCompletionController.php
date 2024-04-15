<?php

namespace App\Http\Controllers\Api\Agents;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AbstractController;
use App\Services\Stream\Streamer;
use Generator;
use App\Services\Agents\Coder\Handler;
use Illuminate\Support\Str;

class CodeCompletionController extends AbstractController
{
    public function __construct(
        private Streamer $streamer,
        private Handler $handler
    ) {
    }


    public function handle(Request $request)
    {
        $params = [
            'prompt' => $request->input('prompt'),
            'language' => $request->input('language'),
        ];

        $generator = $this->handler->handle('gpt-3.5-turbo', $params);

        return response()->stream(
            $this->callbackTest($generator),
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
            // Disable buffering for nginx servers to allow for streaming
            // This is required for the event stream to work
            'X-Accel-Buffering' => 'no',
        ];
    }

    private function callbackTest(Generator $generator)
    {
        return function() use ($generator) {
            $this->streamer->stream($generator);

            $doc = $generator->getReturn();

            $this->streamer->sendEvent('document', $doc);

            $this->streamer->close();
        };
    }
}
