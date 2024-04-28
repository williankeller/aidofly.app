<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\AbstractController;
use App\Models\Library;
use Illuminate\View\View;

class LibraryController extends AbstractController
{
    private $allowedTypes = ['voiceover', 'content'];

    public function index(): View
    {
        $data = json_encode(['delete_success' => __("Content has been deleted successfully.")]);

        return view('pages.library.index', [
            'metaTitle' => __('Content Library'),
            'metaDescription' => __('View all content you created'),
            'xData' => "list('/library', {$data})",
        ]);
    }

    public function show(string $type, string $uuid): View
    {
        if (!in_array($type, $this->allowedTypes)) {
            abort(404);
        }

        try {
            return $this->$type($uuid);
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }

    private function content(string $uuid): View
    {
        $library = Library::where('user_id', auth()->id())
            ->where('uuid', $uuid)->firstOrFail();

        return $this->view(
            "pages.library.show.content",
            $library->title,
            "",
            [
                'library' => $library->makeHidden(['id', 'user_id', 'created_at', 'updated_at']),
                'xData' => "content(" . json_encode(['content' => $library->content, 'uuid' => $library->uuid]) . ")",
            ]
        );
    }

    private function voiceover(string $uuid): View
    {
        $library = Library::with('voice')->where('user_id', auth()->id())
            ->where('uuid', $uuid)->firstOrFail();

        return $this->view(
            "pages.library.show.voiceover",
            $library->title,
            __("Using :name, a :gender voice", ['name' => $library->voice->name, 'gender' => $library->voice->gender]),
            [
                'library' => $library->makeHidden(['id', 'user_id', 'created_at', 'updated_at']),
                'xData' => "voiceover(" . json_encode(['content' => $library->content, 'uuid' => $library->uuid]) . ")",
            ]
        );
    }
}
