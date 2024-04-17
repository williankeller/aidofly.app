<?php

namespace App\Http\Controllers\Api\Agents;

use Generator;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AbstractController;
use App\Services\Stream\Streamer;
use App\Services\Agents\Content\Handler;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContentController extends AbstractController
{
    public function __construct(
        private Streamer $streamer,
        private Handler $handler
    ) {
    }

    public function handle(Request $request, ?string $uuid = null): StreamedResponse
    {
        $generator = $this->handler->handle(
            'gpt-3.5-turbo',
            $request->all(),
            $uuid
        );

        return response()->stream(
            $this->callback($generator),
            200,
            $this->streamHeaders()
        );
    }

    private function callback(Generator $generator)
    {
        return function () use ($generator) {
            $this->streamer->stream($generator);

            $content = $generator->getReturn();

            $this->streamer->sendEvent('document', $content);

            $this->streamer->close();
        };
    }
}
