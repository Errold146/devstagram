<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_user_is_authenticated_after_registering(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Ada Lovelace',
            'username' => 'ada_lovelace',
            'email' => 'ada@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirectToRoute('post.index', 'ada_lovelace');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Ada Lovelace',
            'username' => 'ada_lovelace',
            'email' => 'ada@example.com',
        ]);
    }
}
