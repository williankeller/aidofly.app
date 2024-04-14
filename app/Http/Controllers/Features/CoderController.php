<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\AbstractController;

class CoderController extends AbstractController
{
    public function index()
    {
        return view('pages.features.coder.index', [
            'metaTitle' => __('Coding Assistant'),
            'metaDescription' => __('Generate high quality code in seconds.')
        ]);
    }
}
