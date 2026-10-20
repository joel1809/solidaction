<?php

namespace Database\Factories;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Slider> */
class SliderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'subtitle' => 'Agir ensemble',
            'title' => rtrim(fake()->sentence(3), '.'),
            'text' => fake()->sentence(),
            'image' => 'theme/assets/images/slides/slide-v1-1.jpg',
            'button_text' => 'Faire un don',
            'button_url' => '/faire-un-don',
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
