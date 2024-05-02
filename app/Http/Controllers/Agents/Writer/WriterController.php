<?php

namespace App\Http\Controllers\Agents\Writer;

use App\Models\Library;
use App\Http\Controllers\AbstractController;
use App\Services\Agents\Writer\Preset\TemplateParser;
use Illuminate\View\View;

class WriterController extends AbstractController
{
    public function __construct(
        private TemplateParser $parser
    ) {
    }

    /**
     * Show the Free form writer
     * @return View
     */
    public function freeform(): View
    {
        return $this->view(
            view: 'pages.agents.writer.show',
            title: __('Free form writer'),
            description: __('Write your own content from scratch'),
            data: [
                'xData' => 'content(null, null)',
                'creativities' => $this->creativityOptions(),
                'templates' => null,
                'query' => request()->query('q') ?? null,
            ]
        );
    }

    /**
     * Show an already created content from the library to re-send
     *
     * @param string $uuid Library UUID
     * @return View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(string $uuid): View
    {
        $library = Library::with('preset')->where('uuid', $uuid)
            ->where('user_id', auth()->id())
            ->firstOrFail()
            ->makeHidden(['id', 'template', 'user_id', 'created_at', 'updated_at']);

        $presetJson = $library->preset?->toJson() ?? "null";

        if ($library->preset?->template) {
            $template = $this->parser->parse($library->preset->template);
        }

        return $this->view(
            'pages.agents.writer.show',
            $library->preset?->title ?? __('Free form writer'),
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
}
