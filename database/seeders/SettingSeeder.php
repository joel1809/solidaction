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
            ['contact_phone', '+225 07 08 95 66 66', 'contact'],
            ['contact_phone_alt', '+225 07 48 87 08 78', 'contact'],
            ['contact_address', 'Immeuble le Mali la porte 113, Abidjan, Côte d\'Ivoire', 'contact', 'textarea'],
            ['contact_hours', 'Du lundi au vendredi, 8h00 — 17h00', 'contact'],
            ['contact_map', 'https://maps.google.com/maps?q=Immeuble%20le%20Mali%20la%20porte%20113%2C%20Abidjan%2C%20C%C3%B4te%20d%27Ivoire&z=16&output=embed', 'contact'],

            // Réseaux sociaux
            ['social_facebook', 'https://facebook.com/solidaction', 'social'],
            ['social_twitter', 'https://twitter.com/solidaction', 'social'],
            ['social_instagram', 'https://instagram.com/solidaction', 'social'],
            ['social_linkedin', 'https://linkedin.com/company/solidaction', 'social'],
            ['social_youtube', 'https://youtube.com/@solidaction', 'social'],

            // Contenus éditoriaux
            ['about_short', 'SolidAction agit depuis 2013 auprès des familles vulnérables de Côte d\'Ivoire : accès à l\'eau potable, éducation, santé et réponse aux urgences humanitaires.', 'content', 'textarea'],
            ['about_title', 'Depuis 2013 aux côtés des plus fragiles', 'content'],
            ['about_text', implode("\n\n", [
                'Au cœur de Solid’Action bat une flamme qui illumine les vies dans l\'ombre depuis 2013. Nous sommes nés d\'une simple idée, forgée dans l\'esprit de partage et de solidarité, sur les réseaux sociaux.',
                'Notre essence ?',
                'Offrir une main secourable aux communautés en difficulté, non pas avec des solutions temporaires, mais avec des réponses durables, des réponses qui transforment des vies.',
                'Depuis 2020, nous avons franchi un nouveau chapitre dans notre histoire. Nous avons donné à notre engagement une empreinte légale en devenant l\'ONG Solid’Action. Mais notre cœur est resté le même : celui d\'un groupe de personnes unies par une passion commune, animées par le désir ardent de faire une différence dans le monde.',
                'Nous sommes bien plus qu\'une organisation. Nous sommes une famille, liée par des valeurs intangibles : le don de soi, l\'amour authentique pour nos semblables, la solidarité indéfectible et la compassion inépuisable. Ensemble, nous portons un fardeau de responsabilité envers ceux qui ont besoin d\'aide, envers ceux qui cherchent la lumière dans les ténèbres.',
                'Dans chaque geste que nous posons, dans chaque sourire que nous suscitons, dans chaque vie que nous touchons, nous répandons l\'espoir et l\'amour. Et c\'est cela qui fait battre le cœur de Solid’Action.',
            ]), 'content', 'textarea'],
            ['mission_title', 'Un petit don aujourd\'hui, un grand changement demain.', 'content'],
            ['mission_text', 'Nous construisons des projets avec les communautés locales : points d\'eau, écoles, centres de santé et réponse rapide aux situations d\'urgence. Chaque franc est tracé et audité par un cabinet indépendant.', 'content', 'textarea'],
            ['footer_text', 'Depuis 2013, SolidAction mobilise donateurs et bénévoles autour de projets durables en Côte d\'Ivoire : eau potable, écoles, santé et réponse aux urgences.', 'content', 'textarea'],
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
