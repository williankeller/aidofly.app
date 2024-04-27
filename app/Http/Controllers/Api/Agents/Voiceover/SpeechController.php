<?php

namespace App\Http\Controllers\Api\Agents\Voiceover;

use Illuminate\Http\Request;
use App\Services\Stream\Streamer;
use App\Services\Agents\Voiceover\Handler;
use App\Http\Controllers\Api\AbstractController;

class SpeechController extends AbstractController
{

    public function __construct(
        private Streamer $streamer,
        private Handler $handler
    ) {
    }

    public function handle(Request $request)
    {
        //try {
            $speech = $this->handler->handle($request->uuid, [
                'prompt' => $request->prompt,
            ]);

            return response()->json($speech);
        //} catch (\Exception $e) {
            // Handle the case where the generator could not be created or another error occurred.
        //    return response()->json([
        //        'error' => 'Failed to process the request',
        //        'message' => $e->getMessage()
        //    ], 500);
        //}
    }
}
