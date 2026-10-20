<?php

namespace Database\Seeders;

use App\Models\Cause;
use App\Models\Donation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        if (Donation::exists()) {
            return;
        }

        $donors = [
            ['Claudine Ahoua', 'claudine.ahoua@example.com'],
            ['Jean-Marc Kacou', 'jeanmarc.kacou@example.com'],
            ['Nadia Touré', 'nadia.toure@example.com'],
            ['Thierry Ehui', 'thierry.ehui@example.com'],
            ['Awa Traoré', 'awa.traore@example.com'],
            ['Pierre Djédjé', 'pierre.djedje@example.com'],
            ['Lucie Amon', 'lucie.amon@example.com'],
            ['Karim Diabaté', 'karim.diabate@example.com'],
            ['Sophie Gbané', 'sophie.gbane@example.com'],
            ['Groupe Lumen', 'mecenat@lumen.example'],
            ['Fatou Cissé', 'fatou.cisse@example.com'],
            ['Julien Kra', 'julien.kra@example.com'],
        ];

        $amounts = [1000, 2000, 2500, 5000, 5000, 10000, 10000, 15000, 25000, 50000, 75000, 100000, 250000, 500000, 1500000];
        $frequencies = ['one_time', 'one_time', 'one_time', 'monthly', 'monthly', 'yearly'];
        $methods = ['mobile_money', 'mobile_money', 'mobile_money', 'card', 'transfer', 'cash'];
        $messages = [
            'Courage à toute l\'équipe sur place !',
            null,
            'En mémoire de ma grand-mère.',
            null,
            'Merci pour votre travail de terrain.',
            null,
            'Je donne chaque mois, continuez ainsi.',
        ];

        $causes = Cause::pluck('id')->all();

        // Dix-huit mois d'historique pour alimenter les graphiques du tableau de bord.
        for ($monthsAgo = 17; $monthsAgo >= 0; $monthsAgo--) {
            $count = random_int(6, 16);

            for ($i = 0; $i < $count; $i++) {
                [$name, $email] = $donors[array_rand($donors)];
                $createdAt = now()
                    ->subMonths($monthsAgo)
                    ->startOfMonth()
                    ->addDays(random_int(0, 27))
                    ->addHours(random_int(7, 22));

                $status = match (true) {
                    $monthsAgo === 0 && $i % 4 === 0 => 'pending',
                    $i % 17 === 0 => 'failed',
                    default => 'completed',
                };

                Donation::create([
                    'reference' => 'DON-'.$createdAt->format('Ymd').'-'.Str::upper(Str::random(5)),
                    'cause_id' => $causes ? $causes[array_rand($causes)] : null,
                    'donor_name' => $name,
                    'donor_email' => $email,
                    'donor_phone' => '+225 07 '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99),
                    'amount' => $amounts[array_rand($amounts)],
                    'frequency' => $frequencies[array_rand($frequencies)],
                    'payment_method' => $methods[array_rand($methods)],
                    'status' => $status,
                    'is_anonymous' => random_int(1, 10) === 1,
                    'message' => $messages[array_rand($messages)],
                    'paid_at' => $status === 'completed' ? $createdAt : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        Cause::each(fn (Cause $cause) => $cause->recalculateRaised());
    }
}
