<?php

namespace App\Http\Controllers\Agents;

use App\Models\Preset;
use App\Http\Controllers\AbstractController;
use App\Services\Agents\Preset\TemplateParser;
use Illuminate\View\View;

class ContentController extends AbstractController
{
    public function __construct(
        private TemplateParser $parser
    ) {
    }

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
                'creativities' => [
                    "0" => __("Minimal"),
                    "0.5" => __("Balanced"),
                    "1.0" => __("Creative"),
                    "1.1" => __("Innovative"),
                    "1.3" => __("Visionary"),
                    "1.5" => __("Pioneering"),
                    "1.8" => __("Genius"),
                ]
            ]
        );
    }

    public function show(string $uuid): View
    {
        $preset = Preset::select('uuid', 'title', 'description', 'template', 'status')
            ->where('uuid', $uuid)
            ->where('visibility', 'public')
            ->where('status', 1)
            ->firstOrFail()
            ->makeHidden(['id','template']);

        return $this->view(
            'pages.agents.content.show',
            $preset->title,
            $preset->description,
            [
                'xData' => "content({$preset->toJson()})",
                'preset' => $preset,
                'templates' => $this->parser->parse($preset->template),
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
                    "0" => __("Minimal"),
                    "0.5" => __("Balanced"),
                    "1.0" => __("Creative"),
                    "1.1" => __("Innovative"),
                    "1.3" => __("Visionary"),
                    "1.5" => __("Pioneering"),
                    "1.8" => __("Genius"),
                ]
            ]
        );
    }
}
