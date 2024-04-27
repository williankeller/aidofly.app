<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\AbstractController;
use App\Models\Library;
use Illuminate\View\View;

class LibraryController extends AbstractController
{
    public function index(): View
    {
        $data = json_encode(['delete_success' => __("Content has been deleted successfully.")]);

        return view('pages.library.index', [
            'metaTitle' => __('Content Library'),
            'metaDescription' => __('View all content you created'),
            'xData' => "list('/library', {$data})",
        ]);
    }

    public function show(string $uuid): View
    {
        $library = Library::where('user_id', auth()->id())
            ->where('uuid', $uuid)->firstOrFail();

        return $this->view(
            "pages.library.show.{$library->type}",
            $library->title,
            "",
            [
                'library' => $library->makeHidden(['id', 'user_id', 'created_at', 'updated_at']),
                'xData' => "{$library->type}(" . json_encode(['content' => $library->content, 'uuid' => $library->uuid]) . ")",
            ]
        );
    }
}
