<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\AbstractController;
use App\Models\Library;

class FilestorageController extends AbstractController
{
    public function index(string $filename)
    {
        // Get the filename parts
        $fileParts = pathinfo($filename);

        // Filename without extension
        $libraryUuid = $fileParts['filename'];

        // Extension
        $fileExtension = $fileParts['extension'];

        if ($fileExtension !== 'mp3') {
            abort(404);
        }

        $library = Library::select(['content', 'type'])->where('uuid', $libraryUuid)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->file(
            storage_path('app/' . $library->type . '/' . $library->content)
        );
    }
}
