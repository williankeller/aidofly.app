<?php

namespace App\Http\Controllers\Preset;

use App\Http\Controllers\AbstractController;
use App\Services\Agents\Preset\TemplateParser;
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
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return $this->view(
            'pages.presets.types.default',
            __('Predifined preset'),
            __('Default list of preset templates'),
            [
                // @see \App\Http\Controllers\Api\Agents\PresetsController::handle
                'xData' => "list(\"/presets\", {})",
                'isAdmin' => $this->getUser()->isAdmin(),
            ]
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function user(): View
    {
        return $this->view(
            'pages.presets.types.user',
            __('Custom presets'),
            __('List of your custom presets'),
            [
                // @see \App\Http\Controllers\Api\Agents\PresetsController::handle
                'xData' => "list(\"/presets/mine\", {})"
            ]
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function discover(): View
    {
        return $this->view(
            'pages.presets.types.discover',
            __('Worldwide presets'),
            __('See what other users have created publicly'),
            [
                // @see \App\Http\Controllers\Api\Agents\PresetsController::handle
                'xData' => "list(\"/presets/discover\", {})"
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        /** @var \Illuminate\Database\Eloquent\Collection $categories */
        $categories = Category::orderBy('title', 'asc')->get();

        return $this->view(
            'pages.presets.create',
            __('Create a new preset'),
            __('Create a new preset'),
            [
                'categories' => $categories
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

        if ($authUser->isAdmin()) {
            $status = $request->input('status') === 'active' ? true : false;
            $request->merge(['status' => $status]);
        } else {
            $request->merge(['status' => true]);
        }

        $request->validate([
            'visibility' => 'required|string|in:public,private',
            'status' => 'required|boolean',
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
            'source' => $authUser->isAdmin() ? 'system' : 'user',
            'visibility' => $request->visibility,
            'status' => $request->status,
            'title' => $request->title,
            'description' => $request->description,
            'template' => $request->template,
            'icon' => $request->icon ?? null,
            'color' => $request->color ?? $this->getRandomBackgroundColor(),
            'category_id' => $category->id ?? null,
            'user_id' => $authUser->isAdmin() ? null : $authUser->id,
        ]);

        return $this->redirect('presets.user', __('Preset template created successfully!'));
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

        // Check visibility restrictions
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

    private function getRandomBackgroundColor(): string
    {
        // Generate a random color with a reduced brightness
        $red = dechex(rand(0, 175)); // Limiting to 175 to ensure darkness
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
     */
    public function edit(string $uuid): View
    {
        $authUser = $this->getUser();

        $preset = Preset::where('uuid', $uuid);

        if (!$authUser->isAdmin()) {
            $preset = $preset->where('user_id', $authUser->id);
        }

        $preset = $preset->firstOrFail();

        return $this->view(
            'pages.presets.edit',
            __("Editing: ") . $preset->title,
            $preset->description,
            [
                'preset' => $preset,
                'categories' => Category::select(['uuid', 'title'])->orderBy('title', 'asc')->get(),
                'xData' => "{isProcessing: false}"
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

        if ($authUser->isAdmin()) {
            $status = $request->input('status') === 'active' ? true : false;
            $request->merge(['status' => $status]);
        } else {
            $request->merge(['status' => true]);
        }

        $request->validate([
            'visibility' => 'required|string|in:public,private',
            'status' => 'required|boolean',
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
        $preset = Preset::select('id')->where('uuid', $uuid);

        if (!$authUser->isAdmin()) {
            $preset = $preset->where('user_id', $authUser->id);
        }

        $preset = $preset->firstOrFail();

        Preset::where('id', $preset->id)
            ->update([
                'visibility' => $request->visibility,
                'status' => $request->status,
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

        $preset = Preset::where('uuid', $uuid);

        if (!$authUser->isAdmin()) {
            $preset = $preset->where('user_id', $authUser->id);
        }

        $preset->delete();

        return $this->redirect('presets.user', __('Preset template deleted successfully!'));
    }
}
