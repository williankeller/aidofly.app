<?php

namespace App\Services\Studio;

use Illuminate\Support\Facades\Storage;
use App\Models\Library;

class Filestorage
{
    public function getStoragePath(Library $library): string
    {
        return storage_path('app/' . $library->type . '/' . $library->content);
    }

    public function deleteFile(Library $library): void
    {
        $path = $this->getStoragePath($library);

        if (file_exists($path)) {
            Storage::delete($path);
        }
    }

    public function getFileParts(string $filename, ?string $checkExtension = 'mp3'): array
    {
        $fileParts = pathinfo($filename);

        $this->validateFile($fileParts, $checkExtension);

        return $fileParts;
    }

    public function validateFile(array $fileParts, string $extension): void
    {
        if ($fileParts['extension'] !== $extension) {
            abort(404);
        }
    }
}
