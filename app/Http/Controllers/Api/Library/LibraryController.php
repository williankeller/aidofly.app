<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Api\AbstractController;
use App\Models\Library;
use Illuminate\Http\Request;

class LibraryController extends AbstractController
{
    public function index()
    {
        $library = Library::select(['uuid', 'type', 'visibility', 'title', 'created_at'])->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(
            [
                "object" => "list",
                "data" => $library
            ]
        );
    }
}
