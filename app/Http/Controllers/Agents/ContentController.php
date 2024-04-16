<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\AbstractController;

class ContentController extends AbstractController
{
    public function index()
    {
        $config = json_encode([
            'sort' => [
                [
                    'value' => null,
                    'label' => __('Default')
                ],
                [
                    'value' => 'title',
                    'label' => __('Title')
                ]
            ],
            'filters' => [
                [
                    'label' => __('Category'),
                    'model' => 'category',
                    'options' => []
                ]
            ]
        ]);

        return view('pages.agents.content.index', [
            'metaTitle' => __('Content writer presets'),
            'metaDescription' => __('Choose one of the predefined template presets or continue with free form'),
            'xData' => "list(\"/agent/content/presets\", {$config})"
        ]);
    }
}
