<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_authenticated_user_can_comment_on_a_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post(
            route('comments.store', ['user' => $post->user, 'post' => $post]),
            ['comment' => 'Great repository!']
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'parent_id' => null,
            'comment' => 'Great repository!',
        ]);
    }

    public function test_authenticated_user_can_reply_to_a_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();

        $response = $this->actingAs($user)->post(
            route('comments.store', ['user' => $post->user, 'post' => $post]),
            ['comment' => 'Thanks for sharing!', 'parent_id' => $comment->id]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'parent_id' => $comment->id,
            'comment' => 'Thanks for sharing!',
        ]);
    }

    public function test_reply_must_belong_to_the_same_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $otherPost = Post::factory()->create();
        $commentFromOtherPost = Comment::factory()->for($otherPost)->create();

        $response = $this->actingAs($user)->post(
            route('comments.store', ['user' => $post->user, 'post' => $post]),
            ['comment' => 'Invalid reply', 'parent_id' => $commentFromOtherPost->id]
        );

        $response->assertSessionHasErrors('parent_id');
        $this->assertDatabaseMissing('comments', ['comment' => 'Invalid reply']);
    }

    public function test_guest_cannot_comment_on_a_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->post(
            route('comments.store', ['user' => $post->user, 'post' => $post]),
            ['comment' => 'Great repository!']
        );

        $response->assertRedirectToRoute('login');
        $this->assertDatabaseMissing('comments', ['comment' => 'Great repository!']);
    }

    public function test_post_page_displays_comments_and_their_replies(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create(['comment' => 'Top level comment']);
        $reply = Comment::factory()->for($post)->create([
            'parent_id' => $comment->id,
            'comment' => 'A reply to the top level comment',
        ]);

        $response = $this->get(route('posts.show', ['user' => $post->user, 'post' => $post]));

        $response->assertOk();
        $response->assertSee($comment->user->username);
        $response->assertSee('Top level comment');
        $response->assertSee($reply->user->username);
        $response->assertSee('A reply to the top level comment');
    }

    public function test_ajax_comment_request_returns_rendered_comment_and_count(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->postJson(
            route('comments.store', ['user' => $post->user, 'post' => $post]),
            ['comment' => 'Great repository!']
        );

        $response->assertOk();
        $response->assertJson(['is_reply' => false, 'comments_count' => 1]);
        $response->assertJsonStructure(['id', 'html', 'is_reply', 'root_id', 'comments_count']);
        $this->assertStringContainsString('Great repository!', $response->json('html'));
    }

    public function test_ajax_reply_returns_the_thread_root_id(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();
        $reply = Comment::factory()->for($post)->create(['parent_id' => $comment->id]);

        $response = $this->actingAs($user)->postJson(
            route('comments.store', ['user' => $post->user, 'post' => $post]),
            ['comment' => 'Nested reply', 'parent_id' => $reply->id]
        );

        $response->assertOk();
        $response->assertJson(['is_reply' => true, 'root_id' => $comment->id]);
    }

    public function test_guest_can_poll_for_comments_created_after_a_given_id(): void
    {
        $post = Post::factory()->create();
        $existingComment = Comment::factory()->for($post)->create();
        $newComment = Comment::factory()->for($post)->create(['comment' => 'Brand new comment']);

        $response = $this->getJson(route('comments.latest', [
            'user' => $post->user,
            'post' => $post,
            'after' => $existingComment->id,
        ]));

        $response->assertOk();
        $response->assertJsonCount(1, 'items');
        $response->assertJson(['last_id' => $newComment->id, 'comments_count' => 2]);
        $response->assertJsonPath('active_ids', [$existingComment->id, $newComment->id]);
        $this->assertStringContainsString('Brand new comment', $response->json('items.0.html'));
    }

    public function test_comment_author_can_delete_comment_and_all_replies(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->for($author, 'user')->create();
        $reply = Comment::factory()->for($post)->create(['parent_id' => $comment->id]);

        $response = $this->actingAs($author)->deleteJson(route('comments.destroy', $comment));

        $response->assertOk()->assertJson(['id' => $comment->id, 'comments_count' => 0]);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
        $this->assertDatabaseMissing('comments', ['id' => $reply->id]);
    }

    public function test_user_cannot_delete_another_users_comment(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $author->id]);

        $response = $this->actingAs($otherUser)->deleteJson(route('comments.destroy', $comment));

        $response->assertForbidden();
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }

    public function test_guest_cannot_delete_a_comment(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->delete(route('comments.destroy', $comment));

        $response->assertRedirectToRoute('login');
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }

    public function test_polling_reports_replies_with_their_thread_root_id(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();
        $reply = Comment::factory()->for($post)->create(['parent_id' => $comment->id]);

        $response = $this->getJson(route('comments.latest', [
            'user' => $post->user,
            'post' => $post,
            'after' => $comment->id,
        ]));

        $response->assertOk();
        $response->assertJson(['items' => [
            ['id' => $reply->id, 'is_reply' => true, 'root_id' => $comment->id],
        ]]);
    }
}
