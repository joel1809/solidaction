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
            ['site_description', 'SolidAction est une ONG ivoirienne qui mène des projets d\'accès à l\'eau, d\'éducation et de santé auprès des populations vulnérables de Côte d\'Ivoire.', 'general', 'textarea'],
            ['currency_symbol', 'FCFA', 'general'],
            ['site_logo', null, 'general', 'image'],
            ['site_logo_sticky', null, 'general', 'image'],
            ['site_logo_mobile', null, 'general', 'image'],
            ['site_logo_footer', null, 'general', 'image'],

            // Contact
            ['contact_email', 'contact@solidaction.org', 'contact'],
            ['contact_email_secondary', 'dons@solidaction.org', 'contact'],
            ['contact_phone', '+225 27 22 41 18 30', 'contact'],
            ['contact_phone_secondary', '+225 07 08 12 34 56', 'contact'],
            ['contact_address', 'Cocody Riviera 2, 08 BP 1234 Abidjan 08, Côte d\'Ivoire', 'contact', 'textarea'],
            ['contact_hours', 'Du lundi au vendredi, 8h00 — 17h00', 'contact'],
            ['contact_map', 'https://www.openstreetmap.org/export/embed.html?bbox=-3.9800%2C5.3500%2C-3.9500%2C5.3700&layer=mapnik', 'contact'],

            // Réseaux sociaux
            ['social_facebook', 'https://facebook.com/solidaction', 'social'],
            ['social_twitter', 'https://twitter.com/solidaction', 'social'],
            ['social_instagram', 'https://instagram.com/solidaction', 'social'],
            ['social_linkedin', 'https://linkedin.com/company/solidaction', 'social'],
            ['social_youtube', 'https://youtube.com/@solidaction', 'social'],

            // Contenus éditoriaux
            ['about_short', 'SolidAction agit depuis 2004 auprès des familles vulnérables de Côte d\'Ivoire : accès à l\'eau potable, éducation, santé et réponse aux urgences humanitaires.', 'content', 'textarea'],
            ['about_title', 'Vingt ans d\'engagement aux côtés des plus fragiles', 'content'],
            ['about_text', 'Née à Abidjan de l\'initiative de cinq bénévoles, SolidAction est aujourd\'hui présente dans 14 régions de Côte d\'Ivoire. Nous travaillons exclusivement avec des associations communautaires : ce sont elles qui connaissent les besoins, nous apportons le financement, la méthode et le suivi. Chaque projet est audité et ses résultats publiés dans notre rapport annuel.', 'content', 'textarea'],
            ['mission_title', 'Un petit don aujourd\'hui, un grand changement demain.', 'content'],
            ['mission_text', 'Nous construisons des projets avec les communautés locales : points d\'eau, écoles, centres de santé et réponse rapide aux situations d\'urgence. Chaque franc est tracé et audité par un cabinet indépendant.', 'content', 'textarea'],
            ['footer_text', 'Depuis 2004, SolidAction mobilise donateurs et bénévoles autour de projets durables en Côte d\'Ivoire : eau potable, écoles, santé et réponse aux urgences.', 'content', 'textarea'],
            ['intro_video', 'https://www.youtube.com/watch?v=MLpWrANjFbI', 'content'],
            ['stat_beneficiaries', '13700', 'content'],

            // Dons
            ['donation_amounts', '2000,5000,10000,25000,50000', 'donation'],
            ['donation_orange_money', '+225 07 07 72 28 76', 'donation'],
            ['donation_mtn_momo', '+225 05 05 97 76 63', 'donation'],
            ['donation_moov_money', '+225 01 71 61 21 70', 'donation'],
            ['donation_wave', '+225 07 09 09 22 69', 'donation'],
            ['donation_bank_holder', 'SOLID\'ACTION', 'donation'],
            ['donation_bank_name', 'BNI', 'donation'],
            ['donation_iban', 'CI 092 01 005 00239887 0000 03', 'donation'],
            ['donation_bic', null, 'donation'],
            ['donation_tax_notice', 'Chaque don donne lieu à un reçu nominatif envoyé par e-mail. Les entreprises mécènes reçoivent en plus une attestation annuelle récapitulant leurs versements.', 'donation', 'textarea'],
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
