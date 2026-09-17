<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show']),
        ];
    }

    public function index(User $user)
    {
        $posts = Post::where('user_id', $user->id)->paginate(12);

        return view('dashboard', [
            'user' => $user,
            'posts' => $posts,
            'followers' => $user->followers()->get(),
            'following' => $user->following()->get(),
            'followersCount' => $user->followers()->count(),
            'followingCount' => $user->following()->count(),
            'isFollowing' => Auth::check() && $user->followers()->where('follower_id', Auth::id())->exists(),
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function edit(Post $post)
    {
        abort_unless(Auth::id() === (int) $post->user_id, 403);

        return view('posts.edit', compact('post'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => 'required',
            'github_url' => 'required|url',
            'site_url' => 'nullable|url',
        ]);

        Post::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('post.index', Auth::user());
    }

    public function show(User $user, Post $post)
    {
        return view('posts.show', [
            'post' => $post,
            'user' => $user,
            'comments' => $post->comments,
            'lastCommentId' => Comment::where('post_id', $post->id)->max('id') ?? 0,
            'likedBy' => $post->likes()->with('user')->latest()->get(),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        abort_unless(Auth::id() === (int) $post->user_id, 403);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => 'required',
            'github_url' => 'required|url',
            'site_url' => 'nullable|url',
        ]);

        $oldImage = $post->image;
        $post->update($validated);

        if ($oldImage !== $post->image) {
            File::delete(public_path('uploads/'.$oldImage));
        }

        return redirect()->route('posts.show', ['user' => $post->user, 'post' => $post])
            ->with('mensaje', 'Publicación actualizada correctamente.');
    }

    public function destroy(Post $post)
    {
        abort_unless(Auth::id() === (int) $post->user_id, 403);

        $username = $post->user->username;

        Comment::where('post_id', $post->id)->delete();
        File::delete(public_path('uploads/'.$post->image));
        $post->delete();

        return redirect()->route('post.index', $username)->with('mensaje', 'Publicación eliminada correctamente.');
    }
}
