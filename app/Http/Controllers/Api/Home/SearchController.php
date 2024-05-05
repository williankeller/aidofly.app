<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Api\AbstractController;
use App\Models\Library;
use App\Models\Preset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SearchController extends AbstractController
{
    /**
     * Search for presets and library items given a `query` parameter.
     *
     * @param Request $request
     * @return JsonResponse
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

    private function searchPresets(?string $query, ?int $limit = 5): Collection
    {
        if (!$query) {
            return collect([]);
        }

        $presets = $this->searchGlobalPresets($query, $limit);

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

    private function searchLibrary(?string $query, ?int $limit = 5): Collection
    {
        if (!$query) {
            return collect([]);
        }

        $library = Library::where('user_id', auth()->id())
            ->where(function ($subQuery) use ($query) {
                // Group the like conditions inside a sub-query
                $subQuery->where('title', 'like', "%$query%")->orWhere('content', 'like', "%$query%");
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        // define a contract for the library items response
        return $library->map(function ($item) {
            return [
                'user_id' => $item->user_id,
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

    private function searchGlobalPresets(?string $query, ?int $limit = 10): Collection
    {
        if (!$query) {
            return collect([]);
        }

        $userId = auth()->id();

        $presets = Preset::where(function ($query) use ($userId) {
            // Order user-owned presets first
            $query->where('user_id', $userId)
                ->orWhere('source', 'system')
                ->orWhere('visibility', 'public');
        })
            ->where(function ($subQuery) use ($query) {
                // Apply the text-based search to title and description
                $subQuery->where('title', 'like', "%$query%")
                    ->orWhere('description', 'like', "%$query%");
            })
            ->orderByRaw("CASE
                WHEN user_id = ? THEN 1
                WHEN source = 'system' THEN 2
                ELSE 3
            END, title ASC", [$userId])
            ->limit($limit)
            ->get();

        return $presets;
    }

    private function searchSystemPresets(?string $query, ?int $limit = 10): Collection
    {
        $presets = Preset::where('source', 'system')
            ->where('visibility', 'public')
            ->where(function ($subQuery) use ($query) {
                // Group the like conditions inside a sub-query
                $subQuery->where('title', 'like', "%$query%")->orWhere('description', 'like', "%$query%");
            })
            ->orderBy('title', 'asc')
            ->limit($limit)
            ->get();

        return $presets;
    }
}
