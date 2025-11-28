<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSearchController extends Controller
{
    /**
     * Search users by name or email for project/team invitations.
     * Supports FR3.2 and FR8.1 requirements.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
            'exclude' => 'nullable|array',
            'exclude.*' => 'integer',
        ]);

        $query = $request->input('q');
        $exclude = $request->input('exclude', []);

        $users = User::query()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->when(count($exclude) > 0, fn($q) => $q->whereNotIn('id', $exclude))
            ->where('id', '!=', auth()->id()) // Exclude current user
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();

        return response()->json([
            'users' => $users->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
            ]),
        ]);
    }
}
