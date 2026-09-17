<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $post->likes()->firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'liked' => true,
                'likes_count' => $post->likes()->count(),
            ]);
        }

        return redirect()->back();
    }

    public function destroy(Request $request, Post $post)
    {
        $post->likes()->where('user_id', $request->user()->id)->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'liked' => false,
                'likes_count' => $post->likes()->count(),
            ]);
        }

        return redirect()->back();
    }
}
