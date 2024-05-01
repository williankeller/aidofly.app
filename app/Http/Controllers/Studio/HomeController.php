<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\AbstractController;
use Illuminate\View\View;

class HomeController extends AbstractController
{
    public function index(): View
    {
        return $this->view(
            view: 'pages.home.index',
            title: __('Aidofly Studio'),
            data: [
                'xData' => 'home',
            ]
        );
    }
}
