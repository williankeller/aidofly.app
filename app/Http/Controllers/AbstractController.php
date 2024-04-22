<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

abstract class AbstractController
{
    protected function redirect($route, string $message, ?string $type = 'success'): RedirectResponse
    {
        $redirect = redirect();

        if ($route === 'back') {
            $redirect = $redirect->back();
        } else {
            $redirect = $redirect->route($route);
        }

        $redirect = $redirect->withInput()
            ->with($this->message($message, $type));

        return  $redirect;
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

    protected function view(string $view, string $title, string $description, array $data = []): View
    {
        return view($view, [
            'metaTitle' => $title,
            'metaDescription' => $description,
            ...$data,
        ]);
    }
}
