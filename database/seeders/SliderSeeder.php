<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'subtitle' => 'Changer une vie, changer le monde',
                'title' => "Chaque geste\nest une victoire",
                'text' => 'Rejoignez les 12 400 donateurs qui financent nos projets de terrain.',
                'image' => 'theme/assets/images/slides/slide-v1-1.jpg',
                'button_text' => 'Notre mission',
                'button_url' => '/a-propos',
                'button2_text' => 'Faire un don',
                'button2_url' => '/faire-un-don',
            ],
            [
                'subtitle' => 'De l\'eau potable pour tous',
                'title' => "Un forage,\ncinq villages",
                'text' => 'Avec 8 000 € nous équipons un village entier en eau potable.',
                'image' => 'theme/assets/images/slides/slide-v1-2.jpg',
                'button_text' => 'Voir le projet',
                'button_url' => '/causes',
                'button2_text' => 'Je participe',
                'button2_url' => '/faire-un-don',
            ],
            [
                'subtitle' => 'L\'école change tout',
                'title' => "Offrir une année\nd'école complète",
                'text' => '180 € financent la scolarité, les fournitures et les repas d\'un enfant.',
                'image' => 'theme/assets/images/slides/slide-v1-3.jpg',
                'button_text' => 'Parrainer',
                'button_url' => '/faire-un-don',
                'button2_text' => 'Nos causes',
                'button2_url' => '/causes',
            ],
        ];

        foreach ($slides as $index => $slide) {
            Slider::updateOrCreate(
                ['title' => $slide['title']],
                array_merge($slide, ['sort_order' => $index, 'is_active' => true])
            );
        }
    }
}
