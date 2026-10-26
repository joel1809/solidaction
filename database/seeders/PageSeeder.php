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
                'meta_description' => 'Éditeur, hébergement et propriété intellectuelle du site Solid’Action.',
                'content' => <<<'HTML'
                <h3>Éditeur du site</h3>
                <p>Solid’Action, organisation non gouvernementale de droit ivoirien régie par la loi n° 60-315 du 21 septembre 1960 relative aux associations, dont le siège est situé à Cocody Riviera 2, 08 BP 1234 Abidjan 08, Côte d'Ivoire.</p>
                <p>Directrice de la publication : Aminata Koné, directrice générale.</p>
                <h3>Hébergement</h3>
                <p>Le site est hébergé au sein de datacenters certifiés ISO 27001. Les données personnelles sont traitées conformément à la réglementation ivoirienne sur la protection des données.</p>
                <h3>Propriété intellectuelle</h3>
                <p>Les textes et logotypes sont la propriété de Solid’Action ou de ses partenaires. Toute reproduction sans autorisation écrite est interdite, à l'exception des citations courtes accompagnées de la mention de la source.</p>
                <p>Les photographies proviennent de Pexels et sont utilisées sous la licence Pexels : leurs auteurs sont indiqués sur la page <a href="/page/credits-photos">Crédits photos</a>.</p>
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
                    <li>Émission des reçus de don et obligations comptables ;</li>
                    <li>Information sur l'avancement des projets que vous soutenez ;</li>
                    <li>Gestion des inscriptions aux événements et au bénévolat.</li>
                </ul>
                <h3>Vos droits</h3>
                <p>Conformément à la loi n° 2013-450 du 19 juin 2013 relative à la protection des données à caractère personnel, vous disposez d'un droit d'accès, de rectification, d'effacement et d'opposition. Adressez votre demande à contact@solidaction.org : nous répondons sous trente jours. Vous pouvez également saisir l'ARTCI. Nous ne vendons ni ne louons aucune donnée à des tiers.</p>
                <h3>Conservation</h3>
                <p>Les données des donateurs sont conservées dix ans, durée imposée par les obligations comptables. Les candidatures non retenues sont effacées au bout de deux ans.</p>
                HTML,
            ],
            [
                'title' => 'Transparence financière',
                'slug' => 'transparence-financiere',
                'meta_description' => 'Répartition des dépenses, contrôle des comptes et engagements de Solid’Action.',
                'content' => <<<'HTML'
                <h3>Répartition de chaque franc collecté</h3>
                <ul>
                    <li><strong>85 %</strong> financent directement les programmes de terrain ;</li>
                    <li><strong>10 %</strong> couvrent le suivi, l'évaluation indépendante et les audits ;</li>
                    <li><strong>5 %</strong> assurent le fonctionnement administratif de l'organisation.</li>
                </ul>
                <h3>Contrôle externe</h3>
                <p>Nos comptes annuels sont certifiés sans réserve par un commissaire aux comptes agréé. Les rapports des trois derniers exercices sont transmis sur simple demande écrite.</p>
                <h3>Nos engagements</h3>
                <p>Nous publions les résultats de nos projets même lorsqu'ils sont en deçà des objectifs. Aucun don n'est utilisé pour financer une activité politique ou religieuse. Les salaires de l'équipe dirigeante sont publiés dans le rapport annuel.</p>
                HTML,
            ],
            [
                'title' => 'Crédits photos',
                'slug' => 'credits-photos',
                'meta_description' => 'Auteurs et licences des photographies utilisées sur le site Solid’Action.',
                'content' => $this->photoCredits(),
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], array_merge($page, ['is_active' => true]));
        }
    }

    /**
     * Tableau des crédits photos, construit depuis database/seeders/data/photo-credits.php.
     */
    private function photoCredits(): string
    {
        $rows = collect(require __DIR__.'/data/photo-credits.php')
            ->map(function (array $photo) {
                $license = $photo['license_url']
                    ? '<a href="'.e($photo['license_url']).'" target="_blank" rel="noopener license">'.e($photo['license']).'</a>'
                    : e($photo['license']);

                return '<tr>'
                    .'<td><a href="'.e($photo['url']).'" target="_blank" rel="noopener">'.e($photo['title']).'</a></td>'
                    .'<td>'.e($photo['author']).'</td>'
                    .'<td>'.$license.'</td>'
                    .'</tr>';
            })
            ->implode("\n");

        return <<<HTML
        <p>Les photographies de ce site proviennent de <a href="https://www.pexels.com" target="_blank" rel="noopener">Pexels</a> et sont réutilisées conformément à la licence Pexels. Les vues d'Abidjan, de Yamoussoukro et de Korhogo ainsi qu'une partie des portraits ont été prises en Côte d'Ivoire ; les autres scènes illustrent des situations comparables ailleurs en Afrique. Elles ont été recadrées pour s'adapter à la mise en page ; les images originales sont consultables en suivant chaque lien.</p>
        <p>Les personnes photographiées ne sont pas liées à Solid’Action : les portraits illustrent les pages de l'équipe et des témoignages sans représenter les personnes citées.</p>
        <div class="table-scroll">
            <table class="info-table">
                <thead>
                    <tr><th>Photo</th><th>Auteur</th><th>Licence</th></tr>
                </thead>
                <tbody>
        {$rows}
                </tbody>
            </table>
        </div>
        HTML;
    }
}
