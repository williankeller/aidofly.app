<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

abstract class AbstractController
{
    protected function redirect($route, string $message, ?string $type = 'success'): RedirectResponse
    {
        return redirect()
            ->route($route)
            ->withInput()
            ->with($this->message($message, $type));
    }

    protected function message(string $message, string $type): array
    {
        return [
            'message' => [
                'type' => $type,
                'content' => $message,
            ],
        ];
    }
}
