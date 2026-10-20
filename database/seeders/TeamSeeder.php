<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['Aminata Koné', 'Directrice générale', 'theme/assets/images/team/team-v1-1.jpg', 'Juriste de formation, Aminata a dirigé des programmes de développement dans le Nord ivoirien pendant douze ans avant de prendre la direction de SolidAction.'],
            ['Yao Kouadio', 'Responsable des programmes', 'theme/assets/images/team/team-v1-2.jpg', 'Yao conçoit et évalue les projets avec nos partenaires locaux. Il passe un tiers de l\'année sur le terrain.'],
            ['Adjoua N\'Guessan', 'Coordinatrice des bénévoles', 'theme/assets/images/team/team-v1-3.jpg', 'Adjoua accompagne les 240 bénévoles de l\'ONG, du premier entretien à la mission sur le terrain.'],
            ['Ibrahim Coulibaly', 'Responsable eau & assainissement', 'theme/assets/images/team/team-v1-4.jpg', 'Hydrogéologue, Ibrahim supervise les forages, les réseaux et la formation des comités de gestion.'],
            ['Marie-Laure Aka', 'Responsable administrative et financière', 'theme/assets/images/team/team-v2-1.jpg', 'Marie-Laure garantit la traçabilité de chaque franc collecté et prépare les comptes soumis au commissaire aux comptes.'],
            ['Serge Yapi', 'Chargé des partenariats', 'theme/assets/images/team/team-v2-2.jpg', 'Serge construit nos alliances avec les entreprises mécènes, les fondations et les collectivités.'],
            ['Fatou Bamba', 'Responsable communication', 'theme/assets/images/team/team-v2-3.jpg', 'Fatou raconte le terrain : reportages, rapport annuel, réseaux sociaux et relations presse.'],
            ['Paul Kouamé', 'Coordinateur des urgences', 'theme/assets/images/team/team-v3-1.jpg', 'Ancien logisticien, Paul déploie nos réponses d\'urgence en moins de 72 heures.'],
        ];

        foreach ($members as $index => [$name, $role, $photo, $bio]) {
            TeamMember::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'role' => $role,
                    'photo' => $photo,
                    'bio' => $bio,
                    'email' => Str::slug($name, '.').'@solidaction.org',
                    'twitter' => 'https://twitter.com/solidaction',
                    'linkedin' => 'https://linkedin.com/company/solidaction',
                    'facebook' => 'https://facebook.com/solidaction',
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );
        }
    }
}
