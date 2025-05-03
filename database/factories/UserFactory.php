<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $points = fake()->numberBetween(0, 1000);
        
        // تحديد الرتبة بناءً على النقاط
        $rank = 'مبتدئ';
        if ($points >= 1000) {
            $rank = 'خبير';
        } elseif ($points >= 500) {
            $rank = 'محترف';
        } elseif ($points >= 200) {
            $rank = 'متقدم';
        } elseif ($points >= 50) {
            $rank = 'متوسط';
        }
        
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password123'), // استخدام كلمة مرور ثابتة للتسهيل
            'remember_token' => Str::random(10),
            'is_admin' => false,
            'profile_picture' => null,
            'bio' => fake()->paragraph(),
            'points' => $points,
            'rank' => $rank,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}