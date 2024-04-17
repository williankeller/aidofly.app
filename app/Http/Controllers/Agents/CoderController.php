<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\AbstractController;
use App\Models\Library;
use Illuminate\Http\Request;

class CoderController extends AbstractController
{
    public function index()
    {
        return view('pages.agents.coder.create', [
            'metaTitle' => __('Coding Assistant'),
            'metaDescription' => __('Generate high quality code in seconds.'),
            'xData' => 'coder(null)',
            'hasData' => false
        ]);
    }

    public function show(string $uuid)
    {
        $data = Library::where('uuid', $uuid)
            ->where('user_id', auth()->id())
            ->where('type', 'coder')
            ->firstOrFail();

        return view('pages.agents.coder.index', [
            'metaTitle' => $data->title,
            'metaDescription' => __('View the details of a specific coder.'),
            'xData' => 'coder(' . $data->toJson() . ')',
            'hasData' => true
        ]);
    }
}
