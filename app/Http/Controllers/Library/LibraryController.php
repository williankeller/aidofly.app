<?php

namespace App\Http\Controllers\Library;

use Illuminate\View\View;

class LibraryController
{
    public function index(): View
    {
        return view('pages.library.index', [
            'metaTitle' => 'Library',
            'metaDescription' => 'View all content you created',
        ]);
    }

    public function content(): View
    {
        return view('pages.library.content', [
            'metaTitle' => 'Library Content',
            'metaDescription' => 'View all content you created',
        ]);
    }

    public function coder(): View
    {
        $data = json_encode(['delete_success' => __("Content has been deleted successfully.")]);

        return view('pages.library.coder', [
            'metaTitle' => 'Coder Library',
            'metaDescription' => 'View all content you created',
            'xData' => "list(\"/library/content\", {$data})",
        ]);
    }
}
