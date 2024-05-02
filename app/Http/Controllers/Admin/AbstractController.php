<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\AbstractController as BaseAbstractController;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

abstract class AbstractController extends BaseAbstractController
{
    protected function getUser(): ?User
    {
        return auth()->user();
    }

    protected function view(string $view, string $title, ?string $description = null, ?array $data = []): View
    {
        return view($view, [
            'metaTitle' => $title ?? config('app.name'),
            'metaDescription' => $description,
            ...$data,
        ]);
    }

    protected function redirect($route, string $message, ?string $type = 'success'): RedirectResponse
    {
        $redirect = redirect();

        if ($route === 'back') {
            $redirect = $redirect->back();
        } else {
            $redirect = $redirect->route($route);
        }

        $redirect = $redirect->withInput()->with(
            $this->message($message, $type)
        );

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
}
