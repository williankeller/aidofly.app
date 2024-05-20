<?php

namespace App\Http\Controllers\Api\Agents\Chat;

use App\Http\Controllers\Api\AbstractController;
use App\Services\Stream\Streamer;
use App\Services\Agents\Chat\Handler;
use Illuminate\Http\JsonResponse;
use App\Models\Library;

class ConversationController extends AbstractController
{
    public function __construct(
        private Streamer $streamer,
        private Handler $handler
    ) {
    }

    public function index($uuid): JsonResponse
    {
        $library = Library::select(['uuid', 'title', 'content', 'model'])
            ->where('type', 'chat')
            ->where('user_id', auth()->id())
            ->where('uuid', $uuid)
            ->first();

        if (!$library) {
            return $this->error(__('Conversation not found'), 404);
        }

        return $this->response($library, 'messages');
    }
}
