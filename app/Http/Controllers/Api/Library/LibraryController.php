<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Api\AbstractController;
use App\Models\Library;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LibraryController extends AbstractController
{
    public function index(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 150);
        $starting_after = $request->input('starting_after');

        $query = Library::select(['uuid', 'type', 'visibility', 'title', 'created_at'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');

        // Apply cursor-based pagination
        if ($starting_after) {
            $query->where('uuid', '>', $starting_after);
        }

        $library = $query->limit($limit)->get();

        return response()->json([
            "object" => "list",
            "data" => $library,
            "after" => $request->input('starting_after')
        ]);
    }
}
