<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Jeanne Morel',
                'location' => 'Donatrice mensuelle, Rennes',
                'title' => 'Je sais exactement où va mon don',
                'content' => 'Je donne 20 € par mois depuis quatre ans. Je reçois un bilan de chaque projet, avec les factures et les photos. C\'est la seule association où j\'ai cette impression de transparence totale.',
            ],
            [
                'name' => 'Ousmane Sylla',
                'location' => 'Chef du village de Boundiali',
                'title' => 'Le projet a été construit avec nous',
                'content' => 'Les équipes sont venues écouter avant de proposer. Le comité de gestion est composé d\'habitants, et nous assurons nous-mêmes l\'entretien du forage. C\'est notre ouvrage, pas le leur.',
            ],
            [
                'name' => 'Camille Dufour',
                'location' => 'Bénévole depuis 2019, Lyon',
                'title' => 'Une équipe qui fait ce qu\'elle dit',
                'content' => 'J\'ai commencé par tenir un stand sur un marché, j\'anime aujourd\'hui les formations des nouveaux bénévoles. On m\'a fait confiance très vite, et toujours avec un accompagnement sérieux.',
            ],
            [
                'name' => 'Dr Fatima Zerrouki',
                'location' => 'Médecin partenaire, Zinder',
                'title' => 'La maternité a changé de visage',
                'content' => 'Nous avons reçu du matériel adapté et, surtout, une formation continue pour nos six sages-femmes. La mortalité néonatale du district a reculé de manière mesurable.',
            ],
            [
                'name' => 'Groupe Lumen SAS',
                'location' => 'Entreprise mécène, Nantes',
                'title' => 'Un partenariat exigeant et clair',
                'content' => 'Nous finançons deux projets par an. Le reporting est précis, les délais tenus, et nos salariés peuvent participer aux missions de terrain. Un partenariat que nous renouvelons sans hésiter.',
            ],
        ];

        foreach ($testimonials as $index => $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name']],
                array_merge($testimonial, [
                    'photo' => 'theme/assets/images/testimonial/testimonial-'.($index + 1).'.png',
                    'rating' => 5,
                    'sort_order' => $index,
                    'is_active' => true,
                ])
            );
        }
    }
}
