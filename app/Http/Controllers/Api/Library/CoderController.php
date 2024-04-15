<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Api\AbstractController;
use App\Models\Library;
use Illuminate\Http\Request;

class CoderController extends AbstractController
{
    public function handle(Request $request)
    {
        $library = Library::where('object', 'coder')->orderBy('created_at', 'desc')->get();

        return response()->json(
            [
                "object" => "list",
                "data" => $library->makeHidden(['id', 'object', 'user_id', 'updated_at'])
            ]
        );
    }
}
