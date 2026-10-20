<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Event> */
class EventFactory extends Factory
{
    public function definition(): array
    {
        $title = rtrim(fake()->unique()->sentence(4), '.');

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->sentence(),
            'description' => '<p>'.fake()->paragraphs(2, true).'</p>',
            'starts_at' => now()->addDays(14)->setTime(18, 0),
            'ends_at' => now()->addDays(14)->setTime(21, 0),
            'location' => fake()->city(),
            'address' => fake()->streetAddress(),
            'organizer' => 'SolidAction',
            'capacity' => 100,
            'ticket_price' => 0,
            'status' => 'published',
        ];
    }

    public function past(): static
    {
        return $this->state(fn () => [
            'starts_at' => now()->subDays(20)->setTime(18, 0),
            'ends_at' => now()->subDays(20)->setTime(21, 0),
        ]);
    }

    public function full(): static
    {
        return $this->state(fn () => ['capacity' => 1]);
    }
}
