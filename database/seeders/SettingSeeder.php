<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Identité
            ['site_name', 'SolidAction', 'general'],
            ['site_tagline', 'Agir ensemble, changer des vies', 'general'],
            ['site_description', 'SolidAction est une ONG française qui finance des projets d\'accès à l\'eau, d\'éducation et de santé auprès des populations vulnérables.', 'general', 'textarea'],
            ['currency_symbol', '€', 'general'],
            ['site_logo', null, 'general', 'image'],
            ['site_logo_sticky', null, 'general', 'image'],
            ['site_logo_mobile', null, 'general', 'image'],
            ['site_logo_footer', null, 'general', 'image'],

            // Contact
            ['contact_email', 'contact@solidaction.org', 'contact'],
            ['contact_email_secondary', 'dons@solidaction.org', 'contact'],
            ['contact_phone', '+33 1 84 80 12 34', 'contact'],
            ['contact_phone_secondary', '+33 1 84 80 12 35', 'contact'],
            ['contact_address', '27 rue de la Solidarité, 75011 Paris, France', 'contact', 'textarea'],
            ['contact_hours', 'Du lundi au vendredi, 9h00 — 18h00', 'contact'],
            ['contact_map', 'https://www.openstreetmap.org/export/embed.html?bbox=2.3750%2C48.8530%2C2.3950%2C48.8650&layer=mapnik', 'contact'],

            // Réseaux sociaux
            ['social_facebook', 'https://facebook.com/solidaction', 'social'],
            ['social_twitter', 'https://twitter.com/solidaction', 'social'],
            ['social_instagram', 'https://instagram.com/solidaction', 'social'],
            ['social_linkedin', 'https://linkedin.com/company/solidaction', 'social'],
            ['social_youtube', 'https://youtube.com/@solidaction', 'social'],

            // Contenus éditoriaux
            ['about_short', 'SolidAction agit depuis 2004 auprès des familles vulnérables : accès à l\'eau potable, éducation, santé et réponse aux urgences humanitaires.', 'content', 'textarea'],
            ['about_title', 'Vingt ans d\'engagement aux côtés des plus fragiles', 'content'],
            ['about_text', 'Née de l\'initiative de cinq bénévoles, SolidAction est aujourd\'hui présente dans 14 pays. Nous travaillons exclusivement avec des associations locales : ce sont elles qui connaissent les besoins, nous apportons le financement, la méthode et le suivi. Chaque projet est audité et ses résultats publiés dans notre rapport annuel.', 'content', 'textarea'],
            ['mission_title', 'Un petit don aujourd\'hui, un grand changement demain.', 'content'],
            ['mission_text', 'Nous construisons des projets avec les communautés locales : points d\'eau, écoles, centres de santé et réponse rapide aux situations d\'urgence. Chaque euro est tracé et audité par un cabinet indépendant.', 'content', 'textarea'],
            ['footer_text', 'Depuis 2004, SolidAction mobilise donateurs et bénévoles autour de projets durables : eau potable, écoles, santé et réponse aux urgences.', 'content', 'textarea'],
            ['intro_video', 'https://www.youtube.com/watch?v=MLpWrANjFbI', 'content'],
            ['stat_beneficiaries', '13700', 'content'],

            // Dons
            ['donation_amounts', '25,50,100,250,500', 'donation'],
            ['donation_iban', 'FR76 3000 4000 5000 0612 3456 789', 'donation'],
            ['donation_bic', 'BNPAFRPPXXX', 'donation'],
            ['donation_tax_notice', 'Votre don est déductible de l\'impôt sur le revenu à hauteur de 66 % dans la limite de 20 % du revenu imposable. Un reçu fiscal vous sera adressé.', 'donation', 'textarea'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting[0]],
                [
                    'value' => $setting[1],
                    'group' => $setting[2],
                    'type' => $setting[3] ?? 'text',
                ]
            );
        }
    }
}
