<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\AbstractController;
use Illuminate\View\View;
use Illuminate\Support\Str;

class GuideController extends AbstractController
{
    public function presets(): View
    {
        // Get the markdown content
        $content = $this->getMarkdownContent('presets');

        // Return the view with the parsed markdown content
        return $this->view(
            view: 'pages.agents.writer.guide',
            title: __('Prompt preset documentation'),
            description: __('Prompt templating is a feature that allows the creation of dynamic prompts with customizable inputs.'),
            data: [
                'content' => $content,
            ]
        );
    }

    private function getMarkdownContent(string $file): string
    {
        // Get the markdown content
        $path = storage_path("app/docs/{$file}.md");
        if (!file_exists($path)) {
            abort(404, 'Document not found');
        }
        $content = file_get_contents($path);

        // Parse markdown content using Stringable::markdown() method
        $content = (string) Str::markdown($content);

        return $content;
    }
}
