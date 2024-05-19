<?php

namespace App\Http\Controllers\Api\Agents\Chat;

use Generator;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AbstractController;
use App\Services\Stream\Streamer;
use App\Services\Agents\Chat\Handler;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends AbstractController
{
    public function __construct(
        private Streamer $streamer,
        private Handler $handler
    ) {
    }

    public function handle(Request $request, ?string $uuid = null): StreamedResponse
    {
        try {
            $generator = $this->handler->handle(
                model: 'gpt-4',
                params: $request->all(),
                uuid: $uuid
            );

            return response()->stream(
                $this->callback($generator),
                200,
                $this->streamHeaders()
            );
        } catch (\Exception $e) {
            logger()->error($e->getMessage(), [$e]);
            return $this->error(__('Failed to process the request'), 500);
        }
    }

    private function callback(Generator $generator)
    {
        return function () use ($generator) {
            try {
                $this->streamer->stream($generator);

                $content = $generator->getReturn();

                $this->streamer->sendEvent("message", $content);
            } catch (\Exception $e) {
                // Log the error or handle it appropriately
                $this->streamer->sendEvent('error', "Something went wrong. Try submitting the again.");
                logger()->error($e->getMessage());
            } finally {
                $this->streamer->close();
            }
        };
    }
}
