<?php

namespace App\Http\Controllers\Agents\Writer;

use App\Http\Controllers\AbstractController;
use App\Services\Agents\Writer\Preset\TemplateParser;
use App\Models\Category;
use App\Models\Preset;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PresetsController extends AbstractController
{
    public function __construct(
        private TemplateParser $parser
    ) {
    }

    /**
     * Display a listing of the system defined agent.writer.presets.
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @see \App\Http\Controllers\Api\Agents\Writer\PresetsController::index
     */
    public function index(): View
    {
        return $this->view(
            view: 'pages.agents.writer.presets.types.default',
            title: __('Preset templates'),
            description: __('System defined list of preset templates'),
            data: [
                'xData' => "list('/presets', {})",
                'isAdmin' => $this->getUser()->isAdministrator(),
            ]
        );
    }

    /**
     * Display a listing of the presets from the authenticated user.
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @see \App\Http\Controllers\Api\Agents\Writer\PresetsController::user
     */
    public function user(): View
    {
        return $this->view(
            'pages.agents.writer.presets.types.user',
            __('Custom presets'),
            __('Custom preset templates you created'),
            [
                'xData' => "list('/presets/mine', {})"
            ]
        );
    }

    /**
     * Display a listing of the public presets from other users.
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @see \App\Http\Controllers\Api\Agents\Writer\PresetsController::discover
     */
    public function discover(): View
    {
        return $this->view(
            'pages.agents.writer.presets.types.discover',
            __('Worldwide presets'),
            __('See what other users have created publicly'),
            [
                'xData' => "list('/presets/discover', {})"
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function create(): View
    {
        return $this->view(
            'pages.agents.writer.presets.create',
            __('Create a new preset'),
            __('Create your own custom preset template'),
            [
                'categories' => Category::select(['uuid', 'title'])->orderBy('title', 'asc')->get()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $authUser = $this->getUser();

        $request->validate([
            'visibility' => 'required|string|in:public,private',
            'title' => 'required|string|max:128',
            'description' => 'required|string|max:255',
            'template' => 'required|string',
            'icon' => 'nullable|string|max:32',
            'color' => 'nullable|string|max:7',
            'category' => 'required|uuid',
        ]);

        // Get the category id given the uuid
        $category = Category::select('id')->where('uuid', $request->category)->first();

        Preset::create([
            'source' => 'user',
            'visibility' => $request->visibility,
            'status' => $request->status ?? true,
            'title' => $request->title,
            'description' => $request->description,
            'template' => $request->template,
            'icon' => $request->icon ?? null,
            'color' => $request->color ?? $this->getRandomBackgroundColor(),
            'category_id' => $category->id ?? null,
            'user_id' => $authUser->id,
        ]);

        return $this->redirect('agent.writer.presets.user', __('Preset template created successfully!'));
    }

    /**
     * Show a preset template and fill in the form the custom params
     * @param string $uuid Preset UUID
     * @return View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(string $uuid): View
    {
        $preset = Preset::select('uuid', 'visibility', 'title', 'description', 'template', 'status', 'user_id')
            ->where('uuid', $uuid)
            ->where('status', 1)
            ->firstOrFail();

        // If the preset is private, only the owner can access it
        if ($preset->visibility === 'private' &&  $preset->user_id !== $this->getUser()->id) {
            abort(404, 'Unauthorized access to this resource.');
        }

        $preset = $preset->makeHidden(['id', 'visibility', 'status', 'template', 'user_id']);

        return $this->view(
            'pages.agents.writer.show',
            $preset->title,
            $preset->description,
            [
                'xData' => "content({$preset->toJson()})",
                'preset' => $preset,
                'templates' => $this->parser->parse($preset->template),
                'tones' => $this->voiceToneOptions(),
                'creativities' => $this->creativityOptions()
            ]
        );
    }

    private function creativityOptions(): array
    {
        return [
            "0.1" => __("Precise"),
            "0.7" => __("Neutral"),
            "1.5" => __("Creative"),
        ];
    }

    public function voiceToneOptions(): array
    {
        return [
            __('Professional'),
            __('Funny'),
            __('Casual'),
            __('Excited'),
            __('Sarcastic'),
            __('Dramatic'),
            __('Secretive')
        ];
    }

    /**
     * Generate a random background color for the preset template
     * @return string
     */
    private function getRandomBackgroundColor(): string
    {
        $red = dechex(rand(0, 175));
        $green = dechex(rand(0, 175));
        $blue = dechex(rand(0, 175));

        // Ensure each part has two digits
        $red = str_pad($red, 2, '0', STR_PAD_LEFT);
        $green = str_pad($green, 2, '0', STR_PAD_LEFT);
        $blue = str_pad($blue, 2, '0', STR_PAD_LEFT);

        // Combine the parts into one hex color string
        $hexColor = '#' . $red . $green . $blue;

        return $hexColor;
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param string $uuid
     * @return View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit(string $uuid): View
    {
        $authUser = $this->getUser();

        $preset = Preset::where('uuid', $uuid)->where('user_id', $authUser->id)->firstOrFail();

        return $this->view(
            'pages.agents.writer.presets.edit',
            __("Editing: :title", ['title' => $preset->title]),
            __("Details: :description", ['description' => $preset->description]),
            [
                'preset' => $preset,
                'categories' => Category::select(['uuid', 'title'])->orderBy('title', 'asc')->get(),
                'xData' => "{}"
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $uuid
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(Request $request, string $uuid): RedirectResponse
    {
        $authUser = $this->getUser();

        $request->validate([
            'visibility' => 'required|string|in:public,private',
            'title' => 'required|string|max:128',
            'description' => 'required|string|max:255',
            'template' => 'required|string',
            'icon' => 'nullable|string|max:32',
            'color' => 'nullable|string|max:7',
            'category' => 'required|uuid',
        ]);

        // Get the category id given the uuid
        $category = Category::select('id')->where('uuid', $request->category)->first();

        // Select preset by uuid to make sure it exists and the user is the owner
        $preset = Preset::select('id')->where('uuid', $uuid)->where('user_id', $authUser->id)->firstOrFail();

        Preset::where('id', $preset->id)
            ->update([
                'visibility' => $request->visibility,
                'status' => $request->status ?? true,
                'title' => $request->title,
                'description' => $request->description,
                'template' => $request->template,
                'icon' => $request->icon ?? null,
                'color' => $request->color ?? $this->getRandomBackgroundColor(),
                'category_id' => $category->id ?? null,
            ]);

        return $this->redirect('back', __('Preset template updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Request $request
     * @param string $uuid
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy(Request $request, string $uuid): RedirectResponse
    {
        $authUser = $this->getUser();

        $request->validate([
            'uuid' => 'required|uuid',
        ]);

        if ($request->uuid !== $uuid) {
            abort(404, 'Invalid request');
        }

        Preset::where('uuid', $uuid)->where('user_id', $authUser->id)->delete();

        return $this->redirect('agent.writer.presets.user', __('Preset template deleted successfully!'));
    }
}
