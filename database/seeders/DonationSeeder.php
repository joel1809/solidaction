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
            ['Claire Fontaine', 'claire.fontaine@example.fr'],
            ['Hugo Bertrand', 'hugo.bertrand@example.fr'],
            ['Nadia Belkacem', 'nadia.belkacem@example.fr'],
            ['Thomas Rivière', 'thomas.riviere@example.fr'],
            ['Awa Traoré', 'awa.traore@example.fr'],
            ['Pierre Vasseur', 'pierre.vasseur@example.fr'],
            ['Lucie Marchand', 'lucie.marchand@example.fr'],
            ['Karim Haddad', 'karim.haddad@example.fr'],
            ['Sophie Delaunay', 'sophie.delaunay@example.fr'],
            ['Entreprise Lumen SAS', 'mecenat@lumen-sas.example'],
            ['Fatou Ndiaye', 'fatou.ndiaye@example.fr'],
            ['Julien Perrot', 'julien.perrot@example.fr'],
        ];

        $amounts = [20, 25, 30, 50, 75, 100, 120, 150, 200, 250, 300, 500, 750, 1000, 2500];
        $frequencies = ['one_time', 'one_time', 'one_time', 'monthly', 'monthly', 'yearly'];
        $methods = ['card', 'card', 'transfer', 'mobile_money', 'paypal', 'cash'];
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
                    'donor_phone' => '+33 6 '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99),
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
