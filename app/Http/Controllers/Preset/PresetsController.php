<?php

namespace App\Http\Controllers\Preset;

use App\Http\Controllers\AbstractController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PresetsController extends AbstractController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return $this->view(
            'pages.presets.index',
            __('Your custom presets'),
            __('List of your custom presets'),
            [
                // @see \App\Http\Controllers\Api\Agents\PresetsController::handle
                'xData' => "list(\"/agent/content/presets\", {})"
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
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
