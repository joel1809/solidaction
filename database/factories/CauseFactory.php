<?php

namespace Database\Factories;

use App\Models\Cause;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Cause> */
class CauseFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);

        return [
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(),
            'description' => '<p>'.fake()->paragraphs(3, true).'</p>',
            'goal_amount' => fake()->numberBetween(5000, 80000),
            'raised_amount' => 0,
            'deadline' => now()->addMonths(4)->toDateString(),
            'location' => fake()->city(),
            'status' => 'published',
            'is_featured' => false,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
