<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\AbstractController;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsageController extends AbstractController
{
    /**
     * Display the usage statistics for the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Assuming you need data related to 'type', pre-fetch it grouped by 'type'
        $libraries = Library::where('user_id', $user->id)
            ->select('type', 'cost')
            ->get()
            ->groupBy('type');

        $agents = $libraries->map(function ($items, $type) {
            return [
                'type' => $type,
                'count' => $items->count(),
                'cost' => $items->sum('cost'),
            ];
        });

        return $this->listing(object: 'usage', data: [
            'total' => $libraries->reduce(function ($carry, $items) {
                return $carry + $items->sum('cost');
            }, 0),
            'quota' => $this->getQuota($user),
            'agents' => $agents
        ]);
    }

    /**
     * Retrieve the quota for the user.
     *
     * @param $user
     * @return mixed
     */
    protected function getQuota($user)
    {
        // Placeholder for user quota fetching logic
        return null;
    }
}
