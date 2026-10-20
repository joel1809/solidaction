<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Mentions légales',
                'slug' => 'mentions-legales',
                'meta_description' => 'Éditeur, hébergement et propriété intellectuelle du site SolidAction.',
                'content' => <<<'HTML'
                <h3>Éditeur du site</h3>
                <p>SolidAction, association loi 1901 déclarée en préfecture de Paris sous le numéro W751234567, dont le siège social est situé 27 rue de la Solidarité, 75011 Paris.</p>
                <p>Directrice de la publication : Amina Diallo, directrice générale.</p>
                <h3>Hébergement</h3>
                <p>Le site est hébergé en France au sein de datacenters certifiés ISO 27001. Les données sont conservées sur le territoire de l'Union européenne.</p>
                <h3>Propriété intellectuelle</h3>
                <p>L'ensemble des contenus (textes, photographies, logotypes) est la propriété de SolidAction ou de ses partenaires. Toute reproduction sans autorisation écrite est interdite, à l'exception des citations courtes accompagnées de la mention de la source.</p>
                HTML,
            ],
            [
                'title' => 'Politique de confidentialité',
                'slug' => 'politique-de-confidentialite',
                'meta_description' => 'Traitement des données personnelles des donateurs et visiteurs du site.',
                'content' => <<<'HTML'
                <h3>Données collectées</h3>
                <p>Nous collectons uniquement les données nécessaires au traitement de votre don, de votre inscription à un événement ou de votre candidature de bénévole : identité, coordonnées et historique de vos échanges avec nous.</p>
                <h3>Finalités</h3>
                <ul>
                    <li>Émission des reçus fiscaux et obligations comptables ;</li>
                    <li>Information sur l'avancement des projets que vous soutenez ;</li>
                    <li>Gestion des inscriptions aux événements et au bénévolat.</li>
                </ul>
                <h3>Vos droits</h3>
                <p>Vous disposez d'un droit d'accès, de rectification, d'effacement et d'opposition. Adressez votre demande à contact@solidaction.org : nous répondons sous trente jours. Nous ne vendons ni ne louons aucune donnée à des tiers.</p>
                <h3>Conservation</h3>
                <p>Les données des donateurs sont conservées dix ans, durée imposée par les obligations fiscales. Les candidatures non retenues sont effacées au bout de deux ans.</p>
                HTML,
            ],
            [
                'title' => 'Transparence financière',
                'slug' => 'transparence-financiere',
                'meta_description' => 'Répartition des dépenses, contrôle des comptes et engagements de SolidAction.',
                'content' => <<<'HTML'
                <h3>Répartition de chaque euro collecté</h3>
                <ul>
                    <li><strong>85 centimes</strong> financent directement les programmes de terrain ;</li>
                    <li><strong>10 centimes</strong> couvrent le suivi, l'évaluation indépendante et les audits ;</li>
                    <li><strong>5 centimes</strong> assurent le fonctionnement administratif de l'association.</li>
                </ul>
                <h3>Contrôle externe</h3>
                <p>Nos comptes annuels sont certifiés sans réserve par un commissaire aux comptes inscrit. Les rapports des trois derniers exercices sont téléchargeables sur demande écrite.</p>
                <h3>Nos engagements</h3>
                <p>Nous publions les résultats de nos projets même lorsqu'ils sont en deçà des objectifs. Aucun don n'est utilisé pour financer une activité politique ou religieuse. Les salaires de l'équipe dirigeante sont publiés dans le rapport annuel.</p>
                HTML,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], array_merge($page, ['is_active' => true]));
        }
    }
}
