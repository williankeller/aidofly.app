<?php

namespace App\Http\Controllers\Agents\Chat;

use App\Http\Controllers\AbstractController;
use Illuminate\View\View;

class ChatController extends AbstractController
{
    public function __construct(
    ) {
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
    public function show(): View
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
}
