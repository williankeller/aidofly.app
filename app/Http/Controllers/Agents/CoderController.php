<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\AbstractController;

class CoderController extends AbstractController
{
    public function index()
    {
        return view('pages.agents.coder.index', [
            'metaTitle' => __('Coding Assistant'),
            'metaDescription' => __('Generate high quality code in seconds.'),
            'xData' => 'coder(null)'
        ]);
    }
}
