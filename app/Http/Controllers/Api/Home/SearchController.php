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

        $presets = $this->searchPresets($query, $limit);
        $library = $this->searchLibrary($query, $limit);

        $results = $presets->merge($library);

        return response()->json([
            'object' => 'list',
            'data' => $results,
        ]);
    }

    private function searchPresets($query, $limit)
    {
        $presets = Preset::where('title', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
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
                'url' => route('agent.writer.presets.show', $item->uuid),
            ];
        });
    }

    private function searchLibrary($query, $limit)
    {
        $library = Library::where('title', 'like', "%$query%")
            ->orWhere('content', 'like', "%$query%")
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
                'url' => route('library.agent.show', [$item->type, $item->uuid]),
            ];
        });
    }
}
