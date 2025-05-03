<?php

namespace Database\Factories;

use App\Models\ForumCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ForumTopic>
 */
class ForumTopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();
        
        return [
            'title' => $title,
            'slug' => Str::slug($title . '-' . Str::random(5)),
            'content' => fake()->paragraphs(rand(3, 6), true),
            'user_id' => User::all()->random()->id,
            'category_id' => ForumCategory::all()->random()->id,
            'is_pinned' => fake()->boolean(10), // 10% احتمالية أن يكون مثبت
            'is_solved' => fake()->boolean(30), // 30% احتمالية أن يكون محلول
            'views' => fake()->numberBetween(0, 500),
        ];
    }
}