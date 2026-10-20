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
                'goal_amount' => 15000000,
                'excerpt' => 'Les 1 800 habitants du village marchent chaque jour 6 km pour puiser une eau non potable au marigot. Un forage profond réglerait durablement le problème.',
                'location' => 'Boundiali, Bagoué',
                'deadline' => '+5 months',
                'is_featured' => true,
                'video_url' => 'https://www.youtube.com/watch?v=MLpWrANjFbI',
            ],
            [
                'title' => 'Une année d\'école pour 300 enfants',
                'category' => 'education',
                'image' => 'theme/assets/images/causes/causes-v1-2.jpg',
                'goal_amount' => 35000000,
                'excerpt' => 'Frais d\'inscription, kits scolaires, tenues et repas de midi pour 300 élèves de l\'école primaire publique de Katiola.',
                'location' => 'Katiola, Hambol',
                'deadline' => '+3 months',
            ],
            [
                'title' => 'Équiper la maternité de Bouna',
                'category' => 'sante',
                'image' => 'theme/assets/images/causes/causes-v1-3.jpg',
                'goal_amount' => 25000000,
                'excerpt' => 'Tables d\'accouchement, couveuses et formation de six sages-femmes pour réduire la mortalité néonatale du district sanitaire.',
                'location' => 'Bouna, Bounkani',
                'deadline' => '+7 months',
            ],
            [
                'title' => 'Urgence inondations à Abobo : abris et kits d\'hygiène',
                'category' => 'urgences-humanitaires',
                'image' => 'theme/assets/images/causes/causes-v1-4.jpg',
                'goal_amount' => 42000000,
                'excerpt' => 'Après les pluies diluviennes de juin, 420 familles ont tout perdu. Nous distribuons bâches, nattes, kits d\'hygiène et pastilles de purification.',
                'location' => 'Abobo, Abidjan',
                'deadline' => '+1 month',
                'is_featured' => true,
            ],
            [
                'title' => 'Cantines scolaires : 40 000 repas',
                'category' => 'alimentation',
                'image' => 'theme/assets/images/causes/causes-v1-5.jpg',
                'goal_amount' => 18000000,
                'excerpt' => 'Un repas chaud par jour fait grimper l\'assiduité scolaire de 40 %. Objectif : couvrir une année complète dans quatre écoles.',
                'location' => 'Odienné, Kabadougou',
                'deadline' => '+4 months',
            ],
            [
                'title' => 'Latrines et hygiène dans six écoles',
                'category' => 'eau-assainissement',
                'image' => 'theme/assets/images/causes/causes-v1-6.jpg',
                'goal_amount' => 12500000,
                'excerpt' => 'Des sanitaires séparés et des points de lavage des mains pour permettre aux adolescentes de rester scolarisées.',
                'location' => 'Daloa, Haut-Sassandra',
                'deadline' => '+6 months',
            ],
            [
                'title' => 'Bourses d\'études pour 25 lycéennes',
                'category' => 'education',
                'image' => 'theme/assets/images/causes/causes-v1-7.jpg',
                'goal_amount' => 20000000,
                'excerpt' => 'Trois ans de lycée financés, du transport aux manuels, pour 25 jeunes filles brillantes issues de familles rurales.',
                'location' => 'Man, Tonkpi',
                'deadline' => '+9 months',
            ],
            [
                'title' => 'Un dispensaire mobile pour les zones isolées',
                'category' => 'sante',
                'image' => 'theme/assets/images/causes/causes-v1-8.jpg',
                'goal_amount' => 47000000,
                'excerpt' => 'Un véhicule médicalisé pour atteindre 22 campements sans accès aux soins, avec vaccination et consultations prénatales.',
                'location' => 'Séguéla, Worodougou',
                'deadline' => '+10 months',
            ],
            [
                'title' => 'Jardins maraîchers gérés par les femmes',
                'category' => 'alimentation',
                'image' => 'theme/assets/images/causes/causes-v1-9.jpg',
                'goal_amount' => 10500000,
                'excerpt' => 'Semences, clôtures, irrigation goutte-à-goutte et formation agricole pour 60 femmes cheffes de ménage.',
                'location' => 'Bouaké, Gbêkê',
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
        <p>À {$location}, les besoins identifiés par nos partenaires locaux sont documentés depuis plusieurs mois. Une mission de diagnostic a été menée avec la chefferie, les enseignants et les agents de santé afin de dimensionner précisément le projet et d'éviter tout doublon avec les programmes publics existants.</p>
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
