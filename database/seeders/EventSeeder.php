<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'Course solidaire de la lagune',
                'starts_at' => '+12 days 07:00',
                'location' => 'Abidjan, Plateau',
                'address' => 'Départ : boulevard de la République, Plateau, Abidjan',
                'capacity' => 400,
                'ticket_price' => 5000,
                'excerpt' => '10 km au bord de la lagune Ébrié : chaque dossard finance une semaine de cantine scolaire.',
                'image' => 'theme/assets/images/events/event-1.jpg',
                'is_featured' => true,
            ],
            [
                'title' => 'Dîner caritatif annuel',
                'starts_at' => '+28 days 19:30',
                'location' => 'Abidjan, Cocody',
                'address' => 'Hôtel partenaire, Cocody, Abidjan (adresse envoyée après inscription)',
                'capacity' => 180,
                'ticket_price' => 50000,
                'excerpt' => 'Une soirée de gala avec vente aux enchères au profit de la maternité de Bouna.',
                'image' => 'theme/assets/images/events/event-2.jpg',
            ],
            [
                'title' => 'Webinaire : donner par Mobile Money',
                'starts_at' => '+6 days 18:00',
                'location' => 'En ligne (Zoom)',
                'address' => 'Lien envoyé après inscription',
                'capacity' => 500,
                'ticket_price' => 0,
                'excerpt' => '45 minutes pour tout savoir sur le don par Orange Money, MTN MoMo, Moov Money et Wave, questions comprises.',
                'image' => 'theme/assets/images/events/event-3.jpg',
            ],
            [
                'title' => 'Formation des nouveaux bénévoles',
                'starts_at' => '+20 days 09:00',
                'location' => 'Abidjan, siège Solid’Action',
                'address' => 'Cocody Riviera 2, Abidjan',
                'capacity' => 40,
                'ticket_price' => 0,
                'excerpt' => 'Une journée pour découvrir nos programmes, nos méthodes et choisir votre mission.',
                'image' => 'theme/assets/images/events/event-4.jpg',
            ],
            [
                'title' => 'Marché de Noël solidaire',
                'starts_at' => '+45 days 10:00',
                'location' => 'Grand-Bassam',
                'address' => 'Quartier France, Grand-Bassam',
                'capacity' => null,
                'ticket_price' => 0,
                'excerpt' => 'Artisanat et produits des coopératives partenaires : 100 % des bénéfices reversés aux projets.',
                'image' => 'theme/assets/images/events/event-5.jpg',
            ],
            [
                'title' => 'Assemblée générale 2025',
                'starts_at' => '-40 days 14:00',
                'location' => 'Abidjan, Plateau',
                'address' => 'Salle de conférence partenaire, Plateau, Abidjan',
                'capacity' => 120,
                'ticket_price' => 0,
                'excerpt' => 'Présentation des comptes certifiés et vote des orientations pour l\'année à venir.',
                'image' => 'theme/assets/images/events/event-6.jpg',
            ],
            [
                'title' => 'Concert caritatif « Voix pour l\'eau »',
                'starts_at' => '-90 days 20:00',
                'location' => 'Abidjan, Treichville',
                'address' => 'Palais de la Culture, Treichville, Abidjan',
                'capacity' => 600,
                'ticket_price' => 10000,
                'excerpt' => 'Retour sur une soirée qui a permis de financer deux forages complets dans le Nord.',
                'image' => 'theme/assets/images/events/event-7.jpg',
            ],
        ];

        foreach ($events as $data) {
            $startsAt = now()->modify($data['starts_at']);

            $event = Event::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'title' => $data['title'],
                    'image' => $data['image'],
                    'excerpt' => $data['excerpt'],
                    'description' => $this->description($data['excerpt'], $data['location']),
                    'starts_at' => $startsAt,
                    'ends_at' => $startsAt->copy()->addHours(4),
                    'location' => $data['location'],
                    'address' => $data['address'],
                    'organizer' => 'Solid’Action',
                    'capacity' => $data['capacity'],
                    'ticket_price' => $data['ticket_price'],
                    'status' => 'published',
                    'is_featured' => $data['is_featured'] ?? false,
                ]
            );

            if ($event->registrations()->doesntExist() && $event->capacity) {
                $participants = [
                    ['Aya Kouassi', 'aya.kouassi@example.com', 'confirmed'],
                    ['Konan Yao', 'konan.yao@example.com', 'confirmed'],
                    ['Mariam Ouédraogo', 'mariam.ouedraogo@example.com', 'pending'],
                    ['Koffi Brou', 'koffi.brou@example.com', 'confirmed'],
                    ['Affoué N\'Dri', 'affoue.ndri@example.com', 'cancelled'],
                ];

                foreach (array_slice($participants, 0, random_int(2, 5)) as [$name, $email, $status]) {
                    $event->registrations()->create([
                        'name' => $name,
                        'email' => $email,
                        'phone' => '+225 07 '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99),
                        'seats' => random_int(1, 3),
                        'status' => $status,
                    ]);
                }
            }
        }
    }

    private function description(string $excerpt, string $location): string
    {
        return <<<HTML
        <p>{$excerpt}</p>
        <h3>Au programme</h3>
        <ul>
            <li>Accueil des participants et remise des badges ;</li>
            <li>Présentation des projets financés cette année, chiffres à l'appui ;</li>
            <li>Témoignage en direct d'une équipe de terrain ;</li>
            <li>Temps d'échange avec les bénévoles et les salariés de l'ONG.</li>
        </ul>
        <h3>Informations pratiques</h3>
        <p>Rendez-vous à {$location}. Un plan d'accès détaillé est envoyé par e-mail et par SMS après votre inscription. Les personnes à mobilité réduite sont priées de nous le signaler afin que nous organisions l'accueil.</p>
        <p>L'inscription est gratuite pour les bénévoles de l'ONG. Les places étant limitées, nous confirmons chaque demande dans les 48 heures.</p>
        HTML;
    }
}
