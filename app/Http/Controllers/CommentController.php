<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['store', 'destroy']),
        ];
    }

    public function store(Request $request, User $user, Post $post)
    {
        // Validar
        $validated = $request->validate([
            'comment' => 'required|max:255',
            'parent_id' => ['nullable', 'exists:comments,id', function ($attribute, $value, $fail) use ($post) {
                if ($value && ! Comment::where('id', $value)->where('post_id', $post->id)->exists()) {
                    $fail('El comentario al que intentas responder no pertenece a esta publicación.');
                }
            }],
        ]);

        // Almacenar Comentario
        $comment = Comment::create([
            ...$validated,
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $comment->id,
                'html' => view('components.comment', [
                    'comment' => $comment->load('user', 'replies'),
                    'post' => $post,
                    'user' => $user,
                    'depth' => $comment->parent_id ? 1 : 0,
                ])->render(),
                'is_reply' => $comment->parent_id !== null,
                'root_id' => $comment->rootId(),
                'comments_count' => $post->commentsCount(),
            ]);
        }

        // Imprimir mensaje
        return back()->with('mensaje', $validated['parent_id'] ?? null ? 'Respuesta Enviada Correctamente.' : 'Comentario Realizado Correctamente.');
    }

    public function latest(Request $request, User $user, Post $post)
    {
        $afterId = (int) $request->query('after', 0);

        $newComments = Comment::where('post_id', $post->id)
            ->where('id', '>', $afterId)
            ->with('user', 'replies')
            ->oldest('id')
            ->get();
        $activeCommentIds = Comment::where('post_id', $post->id)->pluck('id')->values();

        $items = $newComments->map(fn (Comment $comment) => [
            'id' => $comment->id,
            'is_reply' => $comment->parent_id !== null,
            'root_id' => $comment->parent_id ? $comment->rootId() : $comment->id,
            'html' => view('components.comment', [
                'comment' => $comment,
                'post' => $post,
                'user' => $user,
                'depth' => $comment->parent_id ? 1 : 0,
            ])->render(),
        ]);

        return response()->json([
            'items' => $items,
            'last_id' => $newComments->max('id') ?? $afterId,
            'comments_count' => $post->commentsCount(),
            'active_ids' => $activeCommentIds,
        ]);
    }

    public function destroy(Request $request, Comment $comment)
    {
        abort_unless(Auth::id() === (int) $comment->user_id, 403);

        $post = $comment->post;
        $commentId = $comment->id;

        $comment->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $commentId,
                'comments_count' => $post->commentsCount(),
            ]);
        }

        return back()->with('mensaje', 'Comentario eliminado correctamente.');
    }
}
