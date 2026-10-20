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
                'title' => 'Course solidaire des berges',
                'starts_at' => '+12 days 09:00',
                'location' => 'Paris, Quai de Seine',
                'address' => 'Parc de Bercy, 75012 Paris',
                'capacity' => 400,
                'ticket_price' => 15,
                'excerpt' => '10 km au bord de l\'eau : chaque dossard finance une semaine de cantine scolaire.',
                'image' => 'theme/assets/images/events/event-details-1.jpg',
                'is_featured' => true,
            ],
            [
                'title' => 'Dîner caritatif annuel',
                'starts_at' => '+28 days 19:30',
                'location' => 'Lyon, Salle Guimet',
                'address' => '28 boulevard des Belges, 69006 Lyon',
                'capacity' => 180,
                'ticket_price' => 85,
                'excerpt' => 'Une soirée de gala avec vente aux enchères au profit de la maternité de Zinder.',
                'image' => 'theme/assets/images/events/event-details-1.jpg',
            ],
            [
                'title' => 'Webinaire : comprendre le reçu fiscal',
                'starts_at' => '+6 days 18:00',
                'location' => 'En ligne (Zoom)',
                'address' => 'Lien envoyé après inscription',
                'capacity' => 500,
                'ticket_price' => 0,
                'excerpt' => '45 minutes pour tout savoir sur la déduction fiscale de vos dons, questions comprises.',
                'image' => 'theme/assets/images/events/event-details-1.jpg',
            ],
            [
                'title' => 'Formation des nouveaux bénévoles',
                'starts_at' => '+20 days 10:00',
                'location' => 'Paris, siège SolidAction',
                'address' => '27 rue de la Solidarité, 75011 Paris',
                'capacity' => 40,
                'ticket_price' => 0,
                'excerpt' => 'Une journée pour découvrir nos programmes, nos méthodes et choisir votre mission.',
                'image' => 'theme/assets/images/events/event-details-1.jpg',
            ],
            [
                'title' => 'Marché de Noël solidaire',
                'starts_at' => '+45 days 10:00',
                'location' => 'Bordeaux, Place Pey-Berland',
                'address' => 'Place Pey-Berland, 33000 Bordeaux',
                'capacity' => null,
                'ticket_price' => 0,
                'excerpt' => 'Artisanat des coopératives partenaires : 100 % des bénéfices reversés aux projets.',
                'image' => 'theme/assets/images/events/event-details-1.jpg',
            ],
            [
                'title' => 'Assemblée générale 2025',
                'starts_at' => '-40 days 14:00',
                'location' => 'Paris, Maison des associations',
                'address' => '11 rue Caillaux, 75013 Paris',
                'capacity' => 120,
                'ticket_price' => 0,
                'excerpt' => 'Présentation des comptes certifiés et vote des orientations pour l\'année à venir.',
                'image' => 'theme/assets/images/events/event-details-1.jpg',
            ],
            [
                'title' => 'Concert caritatif « Voix pour l\'eau »',
                'starts_at' => '-90 days 20:00',
                'location' => 'Nantes, Le Lieu Unique',
                'address' => '2 rue de la Biscuiterie, 44000 Nantes',
                'capacity' => 600,
                'ticket_price' => 22,
                'excerpt' => 'Retour sur une soirée qui a permis de financer deux forages complets.',
                'image' => 'theme/assets/images/events/event-details-1.jpg',
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
                    'organizer' => 'SolidAction',
                    'capacity' => $data['capacity'],
                    'ticket_price' => $data['ticket_price'],
                    'status' => 'published',
                    'is_featured' => $data['is_featured'] ?? false,
                ]
            );

            if ($event->registrations()->doesntExist() && $event->capacity) {
                $participants = [
                    ['Élodie Garnier', 'elodie.garnier@example.fr', 'confirmed'],
                    ['Samir Oualid', 'samir.oualid@example.fr', 'confirmed'],
                    ['Béatrice Nollet', 'beatrice.nollet@example.fr', 'pending'],
                    ['Yann Lebrun', 'yann.lebrun@example.fr', 'confirmed'],
                    ['Inès Cardoso', 'ines.cardoso@example.fr', 'cancelled'],
                ];

                foreach (array_slice($participants, 0, random_int(2, 5)) as [$name, $email, $status]) {
                    $event->registrations()->create([
                        'name' => $name,
                        'email' => $email,
                        'phone' => '+33 6 '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99),
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
            <li>Temps d'échange avec les bénévoles et les salariés de l'association.</li>
        </ul>
        <h3>Informations pratiques</h3>
        <p>Rendez-vous à {$location}. L'accès est possible en transports en commun ; un plan détaillé est envoyé par e-mail après votre inscription. Les personnes à mobilité réduite sont priées de nous le signaler afin que nous organisions l'accueil.</p>
        <p>L'inscription est gratuite pour les bénévoles de l'association. Les places étant limitées, nous confirmons chaque demande par e-mail dans les 48 heures.</p>
        HTML;
    }
}
