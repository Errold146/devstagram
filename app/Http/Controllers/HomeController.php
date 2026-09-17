<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 12;
        $page = LengthAwarePaginator::resolveCurrentPage();

        $posts = Post::query()
            ->with('user')
            ->withMax('likes as likes_max_created_at', 'created_at')
            ->withMax('comments as comments_max_created_at', 'created_at')
            ->get()
            ->map(function (Post $post) {
                $candidates = ['Nueva publicación' => $post->created_at];

                if ($post->updated_at && $post->updated_at->gt($post->created_at)) {
                    $candidates['Publicación actualizada'] = $post->updated_at;
                }

                if ($post->user?->updated_at && $post->user->updated_at->gt($post->user->created_at)) {
                    $candidates['Perfil actualizado'] = $post->user->updated_at;
                }

                if ($post->likes_max_created_at) {
                    $candidates['Nuevo me gusta'] = Carbon::parse($post->likes_max_created_at);
                }

                if ($post->comments_max_created_at) {
                    $candidates['Nuevo comentario'] = Carbon::parse($post->comments_max_created_at);
                }

                $label = null;
                $activityAt = null;

                foreach ($candidates as $candidateLabel => $candidateAt) {
                    if (! $activityAt || $candidateAt->gt($activityAt)) {
                        $activityAt = $candidateAt;
                        $label = $candidateLabel;
                    }
                }

                $post->activity_label = $label;
                $post->activity_at = $activityAt;

                return $post;
            })
            ->sortByDesc('activity_at')
            ->values();

        $items = $posts->forPage($page, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $items,
            $posts->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('principal', ['posts' => $paginator]);
    }
}
