<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Partner> */
class PartnerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'logo' => 'theme/assets/images/brand/brand-logo-1.png',
            'url' => fake()->url(),
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
