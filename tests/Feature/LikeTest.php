<?php

namespace Tests\Feature;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_authenticated_user_can_like_a_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.likes.store', $post));

        $response->assertRedirect();
        $this->assertDatabaseHas('likes', ['post_id' => $post->id, 'user_id' => $user->id]);
    }

    public function test_authenticated_user_can_remove_their_like(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Like::create(['post_id' => $post->id, 'user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('posts.likes.destroy', $post));

        $response->assertRedirect();
        $this->assertDatabaseMissing('likes', ['post_id' => $post->id, 'user_id' => $user->id]);
    }

    public function test_guest_cannot_like_or_remove_a_like(): void
    {
        $post = Post::factory()->create();

        $this->post(route('posts.likes.store', $post))->assertRedirectToRoute('login');
        $this->delete(route('posts.likes.destroy', $post))->assertRedirectToRoute('login');
    }
}
