<?php

namespace Database\Factories;

use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Volunteer> */
class VolunteerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'interest' => 'logistique',
            'availability' => 'Week-ends',
            'message' => fake()->sentence(10),
            'status' => 'pending',
        ];
    }
}
