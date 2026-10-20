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
            ['Amina Diallo', 'Directrice générale', 'theme/assets/images/team/team-v1-1.jpg', 'Juriste de formation, Amina a dirigé des programmes humanitaires au Sahel pendant douze ans avant de prendre la direction de SolidAction.'],
            ['Marc Lefèvre', 'Responsable des programmes', 'theme/assets/images/team/team-v1-2.jpg', 'Marc conçoit et évalue les projets avec nos partenaires locaux. Il passe un tiers de l\'année sur le terrain.'],
            ['Clara Monteiro', 'Coordinatrice des bénévoles', 'theme/assets/images/team/team-v1-3.jpg', 'Clara accompagne les 240 bénévoles de l\'association, du premier entretien à la mission sur le terrain.'],
            ['Idriss Camara', 'Responsable eau & assainissement', 'theme/assets/images/team/team-v1-4.jpg', 'Hydrogéologue, Idriss supervise les forages, les réseaux et la formation des comités de gestion.'],
            ['Hélène Rousseau', 'Responsable administrative et financière', 'theme/assets/images/team/team-v2-1.jpg', 'Hélène garantit la traçabilité de chaque euro collecté et prépare les comptes soumis au commissaire aux comptes.'],
            ['Samuel Okoye', 'Chargé des partenariats', 'theme/assets/images/team/team-v2-2.jpg', 'Samuel construit nos alliances avec les entreprises mécènes, les fondations et les collectivités.'],
            ['Nour Haddad', 'Responsable communication', 'theme/assets/images/team/team-v2-3.jpg', 'Nour raconte le terrain : reportages, rapport annuel, réseaux sociaux et relations presse.'],
            ['Paul Mercier', 'Coordinateur des urgences', 'theme/assets/images/team/team-v3-1.jpg', 'Ancien logisticien, Paul déploie nos réponses d\'urgence en moins de 72 heures.'],
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
