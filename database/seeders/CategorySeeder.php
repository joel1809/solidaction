<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $causeCategories = [
            ['Eau & assainissement', 'Forages, puits et réseaux d\'eau potable.'],
            ['Éducation', 'Écoles, fournitures et bourses scolaires.'],
            ['Santé', 'Dispensaires, vaccination et maternités.'],
            ['Urgences humanitaires', 'Réponse rapide aux catastrophes.'],
            ['Alimentation', 'Cantines scolaires et sécurité alimentaire.'],
        ];

        foreach ($causeCategories as $index => [$name, $description]) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'type' => 'cause', 'description' => $description, 'sort_order' => $index, 'is_active' => true]
            );
        }

        $postCategories = [
            ['Reportages', 'Les récits de nos équipes sur le terrain.'],
            ['Vie de l\'association', 'Coulisses, gouvernance et bénévolat.'],
            ['Conseils aux donateurs', 'Fiscalité, legs et dons réguliers.'],
            ['Plaidoyer', 'Nos prises de position publiques.'],
        ];

        foreach ($postCategories as $index => [$name, $description]) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'type' => 'post', 'description' => $description, 'sort_order' => $index, 'is_active' => true]
            );
        }
    }
}
