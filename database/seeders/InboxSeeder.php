<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\Subscriber;
use App\Models\Volunteer;
use Illuminate\Database\Seeder;

class InboxSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [
            ['Brigitte Lenoir', 'brigitte.lenoir@example.fr', 'Legs et donation', 'Bonjour, je souhaiterais inclure votre association dans mon testament. Qui peut m\'accompagner dans cette démarche ?', 'new'],
            ['Service RSE — Lumen SAS', 'rse@lumen-sas.example', 'Mécénat d\'entreprise 2026', 'Nous souhaitons renouveler notre partenariat et l\'étendre à un second projet. Pouvons-nous convenir d\'un rendez-vous en novembre ?', 'read'],
            ['Antoine Bocquet', 'antoine.bocquet@example.fr', 'Reçu fiscal manquant', 'Je n\'ai pas reçu le reçu fiscal pour mes dons de l\'an dernier. Mon numéro de donateur est 48221.', 'replied'],
            ['Lycée Jean-Jaurès', 'cdi@lycee-jaures.example', 'Intervention en classe', 'Nos élèves de terminale préparent un dossier sur l\'aide au développement. Un bénévole pourrait-il venir témoigner ?', 'new'],
            ['Nadia Belkacem', 'nadia.belkacem@example.fr', 'Félicitations', 'Votre rapport annuel est le plus lisible que j\'ai jamais lu dans le secteur. Merci pour cet effort de clarté.', 'read'],
        ];

        foreach ($messages as $index => [$name, $email, $subject, $message, $status]) {
            ContactMessage::updateOrCreate(
                ['email' => $email, 'subject' => $subject],
                [
                    'name' => $name,
                    'message' => $message,
                    'status' => $status,
                    'reply' => $status === 'replied' ? 'Bonjour, votre reçu fiscal vous a été renvoyé par e-mail ce matin. Merci de votre fidélité.' : null,
                    'read_at' => $status === 'new' ? null : now()->subDays($index),
                    'created_at' => now()->subDays($index * 2 + 1),
                ]
            );
        }

        $volunteers = [
            ['Léa Fournier', 'lea.fournier@example.fr', 'communication', 'Deux soirs par semaine', 'approved', 'Graphiste freelance, je peux prendre en charge vos supports de collecte.'],
            ['Mehdi Chaoui', 'mehdi.chaoui@example.fr', 'logistique', 'Week-ends', 'pending', 'Ancien chef d\'entrepôt, disponible pour organiser les distributions.'],
            ['Sylvie Barreau', 'sylvie.barreau@example.fr', 'education', 'Mercredis après-midi', 'approved', 'Professeure des écoles retraitée, je propose du soutien scolaire.'],
            ['Romain Teyssier', 'romain.teyssier@example.fr', 'sante', 'Variable', 'pending', 'Infirmier en poste, je peux assurer des permanences et des formations aux premiers secours.'],
            ['Chloé Pasquier', 'chloe.pasquier@example.fr', 'administration', 'Lundis et jeudis', 'rejected', 'Étudiante en droit, intéressée par le suivi des dossiers de subvention.'],
            ['Bakary Konaté', 'bakary.konate@example.fr', 'terrain', 'Trois mois consécutifs', 'pending', 'Logisticien humanitaire avec deux missions au Sahel.'],
        ];

        foreach ($volunteers as $index => [$name, $email, $interest, $availability, $status, $message]) {
            Volunteer::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'phone' => '+33 6 '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99),
                    'address' => 'France',
                    'interest' => $interest,
                    'availability' => $availability,
                    'message' => $message,
                    'status' => $status,
                    'created_at' => now()->subDays($index * 3 + 2),
                ]
            );
        }

        $emails = [
            'claire.fontaine@example.fr', 'hugo.bertrand@example.fr', 'nadia.belkacem@example.fr',
            'thomas.riviere@example.fr', 'awa.traore@example.fr', 'pierre.vasseur@example.fr',
            'lucie.marchand@example.fr', 'karim.haddad@example.fr', 'sophie.delaunay@example.fr',
            'julien.perrot@example.fr', 'fatou.ndiaye@example.fr', 'elodie.garnier@example.fr',
        ];

        foreach ($emails as $index => $email) {
            Subscriber::updateOrCreate(
                ['email' => $email],
                ['is_active' => true, 'created_at' => now()->subDays($index * 4 + 1)]
            );
        }
    }
}
