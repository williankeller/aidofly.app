<?php

namespace App\Http\Controllers\Library;

use Illuminate\View\View;

class LibraryController
{
    public function index(): View
    {
        return view('pages.library.index', [
            'metaTitle' => __('Library'),
            'metaDescription' => __('View all content you created'),
        ]);
    }

    public function content(): View
    {
        $data = json_encode(['delete_success' => __("Content has been deleted successfully.")]);

        return view('pages.library.content', [
            'metaTitle' => __('Content Library'),
            'metaDescription' => __('View all content you created'),
            'xData' => "list(\"/library/content\", {$data})",
        ]);
    }

    public function coder(): View
    {
        $data = json_encode(['delete_success' => __("Content has been deleted successfully.")]);

        return view('pages.library.coder', [
            'metaTitle' => __('Coder Library'),
            'metaDescription' => __('View all content you created'),
            'xData' => "list(\"/library/coder\", {$data})",
        ]);
    }
}
