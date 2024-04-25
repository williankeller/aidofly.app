<?php

namespace App\Http\Controllers\Agents;

use App\Models\Voice;
use App\Http\Controllers\AbstractController;
use App\Services\Agents\Preset\TemplateParser;
use Illuminate\View\View;

class VoiceoverController extends AbstractController
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
        return view('pages.agents.voiceover.index', [
            'metaTitle' => __('Voice over'),
            'metaDescription' => __('Select one of the following voices to give your words voice.'),
            // @see \App\Http\Controllers\Api\Agents\PresetsController::handle
            'xData' => "list('/agent/voiceover/voices', {$this->getFilters()})"
        ]);
    }

    /**
     * Show the Free form content writer
     * @return View
     */
    public function show(string $uuid): View
    {
        $voice = Voice::where('uuid', $uuid)->where('status', true)->firstOrFail();

        return $this->view(
            'pages.agents.voiceover.show',
            'Voice over',
            'Select one of the following voices to give your words voice.',
            [
                'xData' => '{}',
                'voice' => $voice
            ]
        );
    }

    /**
     * Get the filters for the presets
     * @return string
     */
    private function getFilters(): string
    {
        return json_encode([
            'type' => 'voiceover',
            'status' => 'active'
        ]);
    }
}
