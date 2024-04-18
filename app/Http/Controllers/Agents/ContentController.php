<?php

namespace App\Http\Controllers\Agents;

use App\Models\Library;
use App\Http\Controllers\AbstractController;
use App\Services\Agents\Preset\TemplateParser;
use Illuminate\View\View;

class ContentController extends AbstractController
{
    public function __construct(
        private TemplateParser $parser
    ) {
    }

    public function create(string $uuid): View
    {
        $library = Library::with(['preset', 'category'])
            ->where('uuid', $uuid)
            ->where('user_id', auth()->id())
            ->firstOrFail()
            ->makeHidden(['id', 'template', 'user_id', 'created_at', 'updated_at']);

        $presetJson = $library->preset?->toJson() ?? "null";

        if ($library->preset?->template) {
            $template = $this->parser->parse($library->preset->template);
        }

        return $this->view(
            'pages.agents.content.create',
            $library->preset?->title ?? __('Free form content writer'),
            $library->preset?->description ?? __('Write your own content from scratch'),
            [
                'xData' => "content({$presetJson}, {$library->toJson()})",
                'preset' => $library->preset,
                'templates' => $template ?? [],
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
