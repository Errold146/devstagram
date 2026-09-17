<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(5),
            'description' => $this->faker->sentence(20),
            'image' => $this->faker->uuid().'.jpg',
            'github_url' => 'https://github.com/'.$this->faker->userName().'/'.$this->faker->slug(),
            'user_id' => User::factory(),
        ];
    }
}
