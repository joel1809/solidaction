<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
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

    /**
     * Compte administrateur de l'espace d'administration.
     */
    public function admin(): static
    {
        return $this->state(fn () => ['role' => 'admin', 'is_active' => true]);
    }

    /**
     * Compte éditeur : accès à l'administration sans les réglages système.
     */
    public function editor(): static
    {
        return $this->state(fn () => ['role' => 'editor', 'is_active' => true]);
    }

    /**
     * Compte sans aucun droit sur l'administration.
     */
    public function visitor(): static
    {
        return $this->state(fn () => ['role' => 'visitor', 'is_active' => true]);
    }
}
