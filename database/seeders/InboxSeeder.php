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
            ['Bernadette Kouakou', 'bernadette.kouakou@example.com', 'Legs et donation', 'Bonjour, je souhaiterais inclure votre ONG dans mon testament. Qui peut m\'accompagner dans cette démarche ?', 'new'],
            ['Service RSE — Groupe Lumen', 'rse@lumen.example', 'Mécénat d\'entreprise 2026', 'Nous souhaitons renouveler notre partenariat et l\'étendre à un second projet. Pouvons-nous convenir d\'un rendez-vous en novembre ?', 'read'],
            ['Antoine Gbéhi', 'antoine.gbehi@example.com', 'Reçu de don manquant', 'Je n\'ai pas reçu le reçu pour mes dons de l\'an dernier. Mon numéro de donateur est 48221.', 'replied'],
            ['Collège Les Palmiers', 'direction@college-palmiers.example', 'Intervention en classe', 'Nos élèves de troisième préparent un dossier sur l\'aide au développement. Un bénévole pourrait-il venir témoigner ?', 'new'],
            ['Nadia Touré', 'nadia.toure@example.com', 'Félicitations', 'Votre rapport annuel est le plus lisible que j\'ai jamais lu dans le secteur. Merci pour cet effort de clarté.', 'read'],
        ];

        foreach ($messages as $index => [$name, $email, $subject, $message, $status]) {
            ContactMessage::updateOrCreate(
                ['email' => $email, 'subject' => $subject],
                [
                    'name' => $name,
                    'message' => $message,
                    'status' => $status,
                    'reply' => $status === 'replied' ? 'Bonjour, votre reçu vous a été renvoyé par e-mail ce matin. Merci de votre fidélité.' : null,
                    'read_at' => $status === 'new' ? null : now()->subDays($index),
                    'created_at' => now()->subDays($index * 2 + 1),
                ]
            );
        }

        $volunteers = [
            ['Grâce Lago', 'grace.lago@example.com', 'communication', 'Deux soirs par semaine', 'approved', 'Graphiste freelance, je peux prendre en charge vos supports de collecte.'],
            ['Moussa Konaté', 'moussa.konate@example.com', 'logistique', 'Week-ends', 'pending', 'Ancien chef d\'entrepôt, disponible pour organiser les distributions.'],
            ['Sylvie Zadi', 'sylvie.zadi@example.com', 'education', 'Mercredis après-midi', 'approved', 'Institutrice retraitée, je propose du soutien scolaire.'],
            ['Roland Assouan', 'roland.assouan@example.com', 'sante', 'Variable', 'pending', 'Infirmier en poste, je peux assurer des permanences et des formations aux premiers secours.'],
            ['Chantal Yéo', 'chantal.yeo@example.com', 'administration', 'Lundis et jeudis', 'rejected', 'Étudiante en droit, intéressée par le suivi des dossiers de subvention.'],
            ['Bakary Fofana', 'bakary.fofana@example.com', 'terrain', 'Trois mois consécutifs', 'pending', 'Logisticien humanitaire avec deux missions dans le Nord du pays.'],
        ];

        foreach ($volunteers as $index => [$name, $email, $interest, $availability, $status, $message]) {
            Volunteer::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'phone' => '+225 05 '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99),
                    'address' => 'Abidjan',
                    'interest' => $interest,
                    'availability' => $availability,
                    'message' => $message,
                    'status' => $status,
                    'created_at' => now()->subDays($index * 3 + 2),
                ]
            );
        }

        $emails = [
            'claudine.ahoua@example.com', 'jeanmarc.kacou@example.com', 'nadia.toure@example.com',
            'thierry.ehui@example.com', 'awa.traore@example.com', 'pierre.djedje@example.com',
            'lucie.amon@example.com', 'karim.diabate@example.com', 'sophie.gbane@example.com',
            'julien.kra@example.com', 'fatou.cisse@example.com', 'aya.kouassi@example.com',
        ];

        foreach ($emails as $index => $email) {
            Subscriber::updateOrCreate(
                ['email' => $email],
                ['is_active' => true, 'created_at' => now()->subDays($index * 4 + 1)]
            );
        }
    }
}
