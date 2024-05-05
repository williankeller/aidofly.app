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
        $limit = $request->input('limit', 150);
        $starting_after = $request->input('starting_after');

        $query = Library::select(['uuid', 'type', 'visibility', 'title', 'resource_id', 'created_at'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');

        // Apply cursor-based pagination
        if ($starting_after) {
            $query->where('uuid', '>', $starting_after);
        }

        $library = $query->limit($limit)->get()->makeHidden(['resource_id']);

        // Fetch the preset IDs
        $presetIds = $library->where('type', 'writer')->pluck('resource_id')->filter()->toArray();
        if ($presetIds) {
            // Fetch the resources from the database
            $resources = Preset::select(['title', 'icon', 'color'])->whereIn('id', $presetIds)->get();

            // Attach the resources to the library items
            $library->transform(function ($item) use ($resources) {
                if ($item->type === 'writer' && $item->resource_id) {
                    $item->resource = isset($resources[$item->resource_id]) ? $resources[$item->resource_id] : null;
                }
                return $item;
            });
        }

        return response()->json([
            "object" => "list",
            "data" => $library,
            "after" => $request->input('starting_after')
        ]);
    }
}
