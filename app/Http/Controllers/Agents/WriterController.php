<?php

namespace App\Http\Controllers\Agents;

use App\Models\Library;
use App\Http\Controllers\AbstractController;
use App\Services\Agents\Preset\TemplateParser;
use Illuminate\View\View;

class WriterController extends AbstractController
{
    public function __construct(
        private TemplateParser $parser
    ) {
    }

    /**
     * Show the list of available presets
     * @return View
     */
    public function index(): View
    {
        return view('pages.agents.writer.index', [
            'metaTitle' => __('Writer presets'),
            'metaDescription' => __('Choose one of the predefined template presets or continue with free form'),
            // @see \App\Http\Controllers\Api\Agents\PresetsController::handle
            'xData' => "list(\"/agent/content/presets\", {$this->getFilters()})"
        ]);
    }

    /**
     * Show the Free form content writer
     * @return View
     */
    public function create(): View
    {
        return $this->view(
            'pages.agents.writer.create',
            'Free form content writer',
            'Write your own content from scratch',
            [
                'xData' => 'content(null, null)',
                'creativities' => $this->creativityOptions()
            ]
        );
    }

    /**
     * Show a already created content from the library to re-send
     * @param string $uuid Library UUID
     * @return View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit(string $uuid): View
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
            'pages.agents.writer.edit',
            $library->preset?->title ?? __('Free form content writer'),
            $library->preset?->description ?? __('Write your own content from scratch'),
            [
                'xData' => "content({$presetJson}, {$library->toJson()})",
                'preset' => $library->preset,
                'templates' => $template ?? [],
                'tones' => $this->voiceToneOptions(),
                'creativities' => $this->creativityOptions()
            ]
        );
    }

    private function creativityOptions(): array
    {
        return [
            "0" => __("Minimal"),
            "0.5" => __("Balanced"),
            "1.0" => __("Creative"),
            "1.1" => __("Innovative"),
            "1.3" => __("Visionary"),
            "1.5" => __("Pioneering"),
            "1.8" => __("Genius"),
        ];
    }

    public function voiceToneOptions(): array
    {
        return [
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
        ];
    }

    private function getFilters(): string
    {
        $filters = [
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
        ];
        return json_encode($filters);
    }
}
