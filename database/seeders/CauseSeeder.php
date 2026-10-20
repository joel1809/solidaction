<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Cause;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CauseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::where('type', 'cause')->pluck('id', 'slug');

        $causes = [
            [
                'title' => 'Un forage pour le village de Boundiali',
                'category' => 'eau-assainissement',
                'image' => 'theme/assets/images/causes/causes-v1-1.jpg',
                'goal_amount' => 24000,
                'excerpt' => 'Les 1 800 habitants de Boundiali marchent chaque jour 6 km pour puiser une eau non potable. Un forage profond réglerait durablement le problème.',
                'location' => 'Boundiali, Côte d\'Ivoire',
                'deadline' => '+5 months',
                'is_featured' => true,
                'video_url' => 'https://www.youtube.com/watch?v=MLpWrANjFbI',
            ],
            [
                'title' => 'Une année d\'école pour 300 enfants',
                'category' => 'education',
                'image' => 'theme/assets/images/causes/causes-v1-2.jpg',
                'goal_amount' => 54000,
                'excerpt' => 'Frais de scolarité, fournitures, uniformes et repas de midi pour 300 élèves de l\'école publique de Kolda.',
                'location' => 'Kolda, Sénégal',
                'deadline' => '+3 months',
            ],
            [
                'title' => 'Équiper la maternité de Zinder',
                'category' => 'sante',
                'image' => 'theme/assets/images/causes/causes-v1-3.jpg',
                'goal_amount' => 38000,
                'excerpt' => 'Tables d\'accouchement, couveuses et formation de six sages-femmes pour réduire la mortalité néonatale du district.',
                'location' => 'Zinder, Niger',
                'deadline' => '+7 months',
            ],
            [
                'title' => 'Urgence inondations : abris et kits d\'hygiène',
                'category' => 'urgences-humanitaires',
                'image' => 'theme/assets/images/causes/causes-v1-4.jpg',
                'goal_amount' => 65000,
                'excerpt' => 'Après la crue du fleuve, 420 familles ont tout perdu. Nous distribuons bâches, nattes, kits d\'hygiène et pastilles de purification.',
                'location' => 'Région de Sofala, Mozambique',
                'deadline' => '+1 month',
                'is_featured' => true,
            ],
            [
                'title' => 'Cantines scolaires : 40 000 repas',
                'category' => 'alimentation',
                'image' => 'theme/assets/images/causes/causes-v1-5.jpg',
                'goal_amount' => 28000,
                'excerpt' => 'Un repas chaud par jour fait grimper l\'assiduité scolaire de 40 %. Objectif : couvrir une année complète dans quatre écoles.',
                'location' => 'Tambacounda, Sénégal',
                'deadline' => '+4 months',
            ],
            [
                'title' => 'Latrines et hygiène dans six écoles',
                'category' => 'eau-assainissement',
                'image' => 'theme/assets/images/causes/causes-v1-6.jpg',
                'goal_amount' => 19500,
                'excerpt' => 'Des sanitaires séparés et des points de lavage des mains pour permettre aux adolescentes de rester scolarisées.',
                'location' => 'Ouagadougou, Burkina Faso',
                'deadline' => '+6 months',
            ],
            [
                'title' => 'Bourses d\'études pour 25 lycéennes',
                'category' => 'education',
                'image' => 'theme/assets/images/causes/causes-v1-7.jpg',
                'goal_amount' => 31000,
                'excerpt' => 'Trois ans de lycée financés, du transport aux manuels, pour 25 jeunes filles brillantes issues de familles rurales.',
                'location' => 'Antananarivo, Madagascar',
                'deadline' => '+9 months',
            ],
            [
                'title' => 'Un dispensaire mobile pour les zones isolées',
                'category' => 'sante',
                'image' => 'theme/assets/images/causes/causes-v1-8.jpg',
                'goal_amount' => 72000,
                'excerpt' => 'Un véhicule médicalisé pour atteindre 22 hameaux sans accès aux soins, avec vaccination et consultations prénatales.',
                'location' => 'Région des Hauts-Plateaux, Madagascar',
                'deadline' => '+10 months',
            ],
            [
                'title' => 'Jardins maraîchers gérés par les femmes',
                'category' => 'alimentation',
                'image' => 'theme/assets/images/causes/causes-v1-9.jpg',
                'goal_amount' => 16000,
                'excerpt' => 'Semences, clôtures, irrigation goutte-à-goutte et formation agricole pour 60 femmes cheffes de famille.',
                'location' => 'Dosso, Niger',
                'deadline' => '+5 months',
            ],
        ];

        foreach ($causes as $index => $data) {
            $description = $this->description($data['title'], $data['excerpt'], $data['location']);

            Cause::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'category_id' => $categories[$data['category']] ?? null,
                    'title' => $data['title'],
                    'image' => $data['image'],
                    'excerpt' => $data['excerpt'],
                    'description' => $description,
                    'goal_amount' => $data['goal_amount'],
                    'deadline' => now()->modify($data['deadline'])->toDateString(),
                    'location' => $data['location'],
                    'status' => 'published',
                    'is_featured' => $data['is_featured'] ?? false,
                    'views' => random_int(120, 2400),
                    'sort_order' => $index,
                    'video_url' => $data['video_url'] ?? null,
                ]
            );
        }
    }

    private function description(string $title, string $excerpt, string $location): string
    {
        return <<<HTML
        <p>{$excerpt}</p>
        <h3>Le contexte</h3>
        <p>À {$location}, les besoins identifiés par nos partenaires locaux sont documentés depuis plusieurs mois. Une mission de diagnostic a été menée avec les autorités villageoises, les enseignants et les responsables de santé afin de dimensionner précisément le projet et d'éviter tout doublon avec les programmes publics existants.</p>
        <h3>Ce que votre don finance</h3>
        <ul>
            <li>L'achat du matériel et des équipements, négociés auprès de fournisseurs locaux ;</li>
            <li>La main-d'œuvre et la formation des équipes qui assureront l'entretien ;</li>
            <li>Le suivi sur 24 mois, avec deux visites de contrôle indépendantes ;</li>
            <li>Un comité de gestion villageois formé à la maintenance et à la collecte d'une petite redevance d'usage.</li>
        </ul>
        <h3>Notre engagement de transparence</h3>
        <p>85 % des fonds collectés vont directement au terrain, 10 % couvrent le suivi et l'évaluation, 5 % les frais administratifs. Chaque donateur reçoit un rapport d'avancement à mi-parcours puis un bilan final avec photos, factures consolidées et indicateurs de résultat.</p>
        <blockquote>« {$title} » : un projet construit avec les habitants, pour qu'il leur survive.</blockquote>
        HTML;
    }
}
