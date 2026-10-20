<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            ['Fondation Clairval', 'brand-logo-1.png'],
            ['Groupe Lumen SAS', 'brand-logo-2.png'],
            ['Ville de Nantes', 'brand-logo-3.png'],
            ['Coopérative Terres Vives', 'brand-logo-4.png'],
            ['Mutuelle Horizon', 'brand-logo-v2-1.png'],
            ['Banque Solidaire Européenne', 'brand-logo-v2-2.png'],
        ];

        foreach ($partners as $index => [$name, $logo]) {
            Partner::updateOrCreate(
                ['name' => $name],
                [
                    'logo' => 'theme/assets/images/brand/'.$logo,
                    'url' => '#',
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );
        }
    }
}
