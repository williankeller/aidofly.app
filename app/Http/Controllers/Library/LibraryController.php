<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\AbstractController;
use App\Models\Library;
use App\Services\Studio\Filestorage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LibraryController extends AbstractController
{
    private const ALLOWED_TYPES = ['voiceover', 'writer'];

    public function __construct(
        private Filestorage $filestorage
    ) {
    }

    public function index(): View
    {
        return view('pages.library.index', [
            'metaTitle' => __('Library'),
            'metaDescription' => __('View all content you created'),
            'xData' => "list('/library', {})",
        ]);
    }

    public function show(string $type, string $uuid): View
    {
        if (!in_array($type, self::ALLOWED_TYPES)) {
            abort(404);
        }

        try {
            return $this->$type($uuid);
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }

    public function destroy(string $uuid): RedirectResponse
    {
        $library = Library::where('user_id', auth()->id())
            ->where('uuid', $uuid)
            ->firstOrFail();

        try {
            if ($library->type === 'voiceover') {
                $this->filestorage->deleteFile($library);
            }

            // Delete the library item
            $library->delete();
        } catch (\Exception $e) {
            $this->redirect('back', $e->getMessage());
        }

        return $this->redirect(
            'library.index',
            __(':type content deleted successfully!', ['type' => ucfirst($library->type)])
        );
    }

    private function writer(string $uuid): View
    {
        $library = Library::where('user_id', auth()->id())
            ->where('uuid', $uuid)
            ->firstOrFail();

        return $this->view(
            view: "pages.library.show.writer",
            title: $library->title,
            data: [
                'library' => $library->makeHidden(['id', 'user_id', 'created_at', 'updated_at']),
                'xData' => "content(" . json_encode(['content' => $library->content, 'uuid' => $library->uuid]) . ")",
            ]
        );
    }

    private function voiceover(string $uuid): View
    {
        $library = Library::with('voice')
            ->where('user_id', auth()->id())
            ->where('uuid', $uuid)
            ->firstOrFail();

        return $this->view(
            "pages.library.show.voiceover",
            __($library->title),
            __("Using :name, a :gender voice", ['name' => $library->voice->name, 'gender' => $library->voice->gender]),
            [
                'library' => $library->makeHidden(['id', 'user_id', 'created_at', 'updated_at']),
                'xData' => "voiceover(" . json_encode(['content' => $library->content, 'uuid' => $library->uuid]) . ")",
            ]
        );
    }
}
