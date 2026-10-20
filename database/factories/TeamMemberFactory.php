<?php

namespace Database\Factories;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<TeamMember> */
class TeamMemberFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'role' => 'Bénévole',
            'bio' => fake()->sentence(12),
            'email' => fake()->safeEmail(),
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
