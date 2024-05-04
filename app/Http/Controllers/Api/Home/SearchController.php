<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Api\AbstractController;
use App\Models\Library;
use App\Models\Preset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends AbstractController
{
    /**
     * Search for presets and library items given a `query` parameter.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $request->query('query');
        $limit = $request->query('limit');

        if (!$query) {
            return $this->listing(data: []);
        }

        $presets = $this->searchPresets($query, $limit);
        $library = $this->searchLibrary($query, $limit);

        $results = $presets->merge($library);

        return $this->listing(data: $results);
    }

    private function searchPresets(?string $query, ?int $limit = 5)
    {
        if (!$query) {
            return collect([]);
        }
        $presets = Preset::where('title', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->orderBy('title', 'asc')
            ->limit($limit)
            ->get();

        // define a contract for the preset items response
        return $presets->map(function ($item) {
            return [
                'object' => 'preset',
                'uuid' => $item->uuid,
                'title' => $item->title,
                'description' => $item->description,
                'type' => $item->type,
                'icon' => $item->icon,
                'color' => $item->color,
                'url' => route('agent.writer.presets.show', $item->uuid),
            ];
        })->collect();
    }

    private function searchLibrary(?string $query, ?int $limit = 5)
    {
        if (!$query) {
            return collect([]);
        }

        $library = Library::where('title', 'like', "%$query%")
            ->orWhere('content', 'like', "%$query%")
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        // define a contract for the library items response
        return $library->map(function ($item) {
            return [
                'object' => 'library',
                'uuid' => $item->uuid,
                'title' => $item->title,
                'description' => "{$item->model} model",
                'type' => $item->type,
                'icon' => null,
                'color' => null,
                'url' => route('library.show', [$item->type, $item->uuid]),
            ];
        })->collect();
    }
}
