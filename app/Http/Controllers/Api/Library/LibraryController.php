<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Api\AbstractController;
use App\Models\Library;
use App\Models\Preset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LibraryController extends AbstractController
{
    public function index(Request $request): JsonResponse
    {
        // Set a default limit and starting cursor
        $limit = (int) $request->input('limit', 10);
        $page = (int) $request->input('page', 1);
        $userId = auth()->id();

        // Calculate the offset
        $offset = ($page - 1) * $limit;

        // Create the base query
        $query = Library::select(['uuid', 'type', 'visibility', 'title', 'resource_id', 'created_at'])
            ->skip($offset)
            ->take($limit)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        // Fetch library items and hide resource_id
        $library = $query->limit($limit)->get()->makeHidden(['resource_id']);

        // Collect resource IDs to optimize data fetching
        $presetIds = $library->where('type', 'writer')->pluck('resource_id')->filter()->unique()->values()->all();

        // Fetch preset resources in one query
        $resources = Preset::whereIn('id', $presetIds)
            ->get()
            ->keyBy('id')
            ->map(function ($resource) {
                return collect($resource)->only(['title', 'icon', 'color']);
            });

        // Attach resources to the library items where applicable
        $library->transform(function ($item) use ($resources) {
            if ($item->type === 'writer' && isset($resources[$item->resource_id])) {
                $item->resource = $resources[$item->resource_id];
            } else {
                $item->resource = null;
            }
            return $item;
        });

        $total = Library::where('user_id', $userId)->count();

        // Return the formatted JSON response
        return response()->json([
            "object" => "list",
            "data" => $library,
            "pagination" => [
                "page" => $page,
                "pages" => ceil($total / $limit),
                "total" => $total,
            ],
        ]);
    }
}
