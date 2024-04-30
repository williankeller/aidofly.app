<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\AbstractController;
use App\Models\Library;
use Termwind\Components\Li;

class FilestorageController extends AbstractController
{
    public function index(string $filename)
    {
        // Get the filename parts
        $fileParts = pathinfo($filename);

        if ($fileParts['extension'] !== 'mp3') {
            abort(404);
        }

        $library = Library::select(['content', 'type'])
            ->where('uuid', $fileParts['filename'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->file($this->getStoragePath($library));
    }

    public function download(string $uuid)
    {
        $library = Library::select(['content', 'type'])
            ->where('uuid', $uuid)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->download($this->getStoragePath($library));
    }

    private function getStoragePath(Library $library): string
    {
        return storage_path('app/' . $library->type . '/' . $library->content);
    }
}
