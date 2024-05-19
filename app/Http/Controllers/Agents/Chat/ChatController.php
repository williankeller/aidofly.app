<?php

namespace App\Http\Controllers\Agents\Chat;

use App\Http\Controllers\AbstractController;
use App\Models\Library;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Casts\Json;

class ChatController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * Show an already created writter to be regenerated
     *
     * @param string $uuid Library UUID
     * @return View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function index(): View
    {
        return $this->view(
            view: 'pages.agents.chat.show',
            title: __('Chat with AI'),
            description: __('Chat with AI to generate text content'),
            data: [
                'xData' => "chat({})",
            ]
        );
    }

    /**
     * Show an already created writter to be regenerated
     *
     * @param string $uuid Library UUID
     * @return View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(?string $uuid = null): View
    {
        $library = null;

        if ($uuid) {
            $library = Library::where('type', 'chat')
                ->where('user_id', auth()->id())
                ->where('uuid', $uuid)
                ->firstOrFail();
        }

        return $this->view(
            view: 'pages.agents.chat.show',
            title: $library->title ?? __('Chat with AI'),
            description: __('Chat with AI to generate text content'),
            data: [
                'xData' => "chat({uuid: '{$uuid}')",
                'library' => $library,
                'conversation' => $library ? Json::decode($library->content, false) : [],
            ]
        );
    }
}
