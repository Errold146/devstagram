<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_post_owner_can_delete_the_post_and_its_comments(): void
    {
        $owner = User::factory()->create();
        $post = Post::factory()->for($owner)->create();
        Comment::factory()->for($post)->create();

        $response = $this->actingAs($owner)->delete(route('posts.destroy', $post));

        $response->assertRedirectToRoute('post.index', $owner);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $this->assertDatabaseMissing('comments', ['post_id' => $post->id]);
    }

    public function test_user_cannot_delete_another_users_post(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $response = $this->actingAs($otherUser)->delete(route('posts.destroy', $post));

        $response->assertForbidden();
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_post_owner_can_view_and_update_the_post(): void
    {
        $owner = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $this->actingAs($owner)
            ->get(route('posts.edit', $post))
            ->assertOk()
            ->assertSee('Editar publicación');

        $response = $this->actingAs($owner)->put(route('posts.update', $post), [
            'title' => 'Título actualizado',
            'description' => 'Descripción actualizada',
            'image' => $post->image,
            'github_url' => 'https://github.com/example/updated-repository',
        ]);

        $response->assertRedirectToRoute('posts.show', ['user' => $owner, 'post' => $post]);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Título actualizado',
            'description' => 'Descripción actualizada',
        ]);
    }

    public function test_user_cannot_view_or_update_another_users_post(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $this->actingAs($otherUser)
            ->get(route('posts.edit', $post))
            ->assertForbidden();

        $response = $this->actingAs($otherUser)->put(route('posts.update', $post), [
            'title' => 'No autorizado',
            'description' => 'No autorizado',
            'image' => $post->image,
            'github_url' => 'https://github.com/example/unauthorized',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('posts', ['title' => 'No autorizado']);
    }
}
