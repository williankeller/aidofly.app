<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Api\AbstractController;
use App\Models\Library;
use Illuminate\Http\Request;

class LibraryController extends AbstractController
{
    public function index(Request $request)
    {
        $library = Library::where('type', 'content')->orderBy('created_at', 'desc')->get();

        return response()->json(
            [
                "object" => "list",
                "data" => $library->makeHidden(['id', 'type', 'user_id', 'updated_at'])
            ]
        );
    }
}
