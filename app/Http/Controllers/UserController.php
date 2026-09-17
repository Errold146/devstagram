<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if ($query === '') {
            return response()->json([]);
        }

        $users = User::query()
            ->where('username', 'like', '%'.$query.'%')
            ->orWhere('name', 'like', '%'.$query.'%')
            ->select(['username', 'profile_image'])
            ->orderBy('username')
            ->limit(8)
            ->get()
            ->map(fn (User $user) => [
                'username' => $user->username,
                'avatar' => $user->profile_image ? asset('profiles/'.$user->profile_image) : asset('usuario.svg'),
                'url' => route('post.index', $user->username),
            ]);

        return response()->json($users);
    }
}
