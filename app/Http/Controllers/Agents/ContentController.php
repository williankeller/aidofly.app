<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\AbstractController;
use Illuminate\View\View;

class ContentController extends AbstractController
{
    public function index(): View
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

    public function create(): View
    {
        return $this->view(
            'pages.agents.content.create',
            'Free form content writer',
            'Write your own content from scratch',
            [
                'xData' => 'content(null, null)',
                'tones' => [
                    __('Professional'),
                    __('Funny'),
                    __('Casual'),
                    __('Excited'),
                    __('Witty'),
                    __('Sarcastic'),
                    __('Dramatic'),
                    __('Feminine'),
                    __('Masculine'),
                    __('Grumpy'),
                    __('Bold'),
                    __('Secretive')
                ],
                'creativities' => [
                    "0.0" => __("Minimal"),
                    "0.2" => __("Basic"),
                    "0.3" => __("Modest"),
                    "0.4" => __("Adequate"),
                    "0.5" => __("Balanced"),
                    "0.6" => __("Intermediate"),
                    "0.8" => __("Expressive"),
                    "0.9" => __("Imaginative"),
                    "1.0" => __("Creative"),
                    "1.1" => __("Innovative"),
                    "1.2" => __("Inspired"),
                    "1.3" => __("Visionary"),
                    "1.5" => __("Pioneering"),
                    "1.6" => __("Artistic"),
                    "1.7" => __("Radical"),
                    "1.8" => __("Genius"),
                    "1.9" => __("Transcendent"),
                    "2.0" => __("Boundless")
                ]
            ]
        );
    }
}
