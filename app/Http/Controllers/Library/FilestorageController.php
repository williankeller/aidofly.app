<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\AbstractController;
use App\Models\Library;
use App\Services\Studio\Filestorage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FilestorageController extends AbstractController
{

    public function __construct(
        private Filestorage $filestorage
    ) {
    }

    public function index(string $filename): BinaryFileResponse
    {
        // Get the filename parts
        $fileParts = $this->filestorage->getFileParts($filename);

        $library = Library::select(['content', 'type'])
            ->where('uuid', $fileParts['filename'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->file(
            $this->filestorage->getStoragePath($library)
        );
    }

    public function download(string $uuid): BinaryFileResponse
    {
        $library = Library::select(['content', 'type'])
            ->where('uuid', $uuid)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->download(
            $this->filestorage->getStoragePath($library)
        );
    }
}
