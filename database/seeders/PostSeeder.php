<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::where('type', 'post')->pluck('id', 'slug');
        $authors = User::pluck('id', 'email');

        $posts = [
            [
                'title' => 'À Boundiali, l\'eau coule enfin au centre du village',
                'category' => 'reportages',
                'image' => 'theme/assets/images/blog/blog-v1-1.jpg',
                'excerpt' => 'Six mois après le début du chantier, le forage est opérationnel. Récit d\'une mise en service très attendue.',
                'tags' => ['eau', 'côte d\'ivoire', 'terrain'],
                'days_ago' => 4,
            ],
            [
                'title' => 'Comment devenir bénévole en trois étapes',
                'category' => 'vie-de-l-association',
                'image' => 'theme/assets/images/blog/blog-v1-2.jpg',
                'excerpt' => 'Vous avez du temps, une compétence, ou simplement l\'envie d\'aider ? Voici comment nous rejoindre concrètement.',
                'tags' => ['bénévolat', 'engagement'],
                'days_ago' => 11,
            ],
            [
                'title' => 'Dons et impôts : ce qui change cette année',
                'category' => 'conseils-aux-donateurs',
                'image' => 'theme/assets/images/blog/blog-v1-3.jpg',
                'excerpt' => 'Réduction de 66 %, plafonds, reçus fiscaux dématérialisés : le point complet pour votre déclaration.',
                'tags' => ['fiscalité', 'donation'],
                'days_ago' => 18,
            ],
            [
                'title' => 'Scolariser les filles : le levier le plus rentable',
                'category' => 'plaidoyer',
                'image' => 'theme/assets/images/blog/blog-v1-4.jpg',
                'excerpt' => 'Chaque année de scolarité supplémentaire augmente de 10 % le revenu futur. Pourquoi nous en faisons une priorité.',
                'tags' => ['éducation', 'plaidoyer'],
                'days_ago' => 26,
            ],
            [
                'title' => 'Mozambique : trois semaines après les inondations',
                'category' => 'reportages',
                'image' => 'theme/assets/images/blog/blog-v1-5.jpg',
                'excerpt' => '420 familles relogées, 2 100 kits distribués : bilan d\'étape de notre réponse d\'urgence.',
                'tags' => ['urgence', 'mozambique'],
                'days_ago' => 33,
            ],
            [
                'title' => 'Notre rapport annuel 2024 est en ligne',
                'category' => 'vie-de-l-association',
                'image' => 'theme/assets/images/blog/blog-v1-6.jpg',
                'excerpt' => 'Comptes certifiés, répartition des dépenses, résultats programme par programme : tout est public.',
                'tags' => ['transparence', 'rapport'],
                'days_ago' => 47,
            ],
            [
                'title' => 'Sages-femmes de Zinder : six mois de formation',
                'category' => 'reportages',
                'image' => 'theme/assets/images/blog/blog-v1-7.jpg',
                'excerpt' => 'Elles accompagnent désormais plus de 90 accouchements par mois dans des conditions enfin sécurisées.',
                'tags' => ['santé', 'niger'],
                'days_ago' => 58,
            ],
            [
                'title' => 'Le don mensuel, discret mais décisif',
                'category' => 'conseils-aux-donateurs',
                'image' => 'theme/assets/images/blog/blog-v1-8.jpg',
                'excerpt' => '15 € par mois valent mieux qu\'un don ponctuel de 180 € : nos équipes peuvent enfin planifier.',
                'tags' => ['donation', 'conseils'],
                'days_ago' => 72,
            ],
        ];

        foreach ($posts as $index => $data) {
            Post::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'category_id' => $categories[$data['category']] ?? null,
                    'user_id' => $index % 2 === 0
                        ? ($authors['redaction@solidaction.org'] ?? null)
                        : ($authors['admin@solidaction.org'] ?? null),
                    'title' => $data['title'],
                    'image' => $data['image'],
                    'excerpt' => $data['excerpt'],
                    'body' => $this->body($data['excerpt']),
                    'tags' => $data['tags'],
                    'status' => 'published',
                    'is_featured' => $index < 2,
                    'allow_comments' => true,
                    'views' => random_int(90, 3200),
                    'published_at' => now()->subDays($data['days_ago']),
                    'created_at' => now()->subDays($data['days_ago']),
                ]
            );
        }

        $this->seedComments();
    }

    private function body(string $excerpt): string
    {
        return <<<HTML
        <p>{$excerpt}</p>
        <p>Sur place, le travail commence toujours de la même manière : une série de réunions avec les représentants des habitants, les enseignants et les agents de santé. Ce sont eux qui décident de l'emplacement, du calendrier et des règles d'usage. Notre rôle se limite au financement, à l'ingénierie et au contrôle de la qualité des travaux.</p>
        <h3>Des résultats mesurés, pas estimés</h3>
        <p>Chaque projet est assorti d'indicateurs définis avant le premier euro dépensé : nombre de bénéficiaires, taux de fréquentation, distance moyenne parcourue, taux de panne à douze mois. Les relevés sont effectués par un prestataire indépendant, et publiés même lorsqu'ils sont décevants.</p>
        <blockquote>« Ce qui compte n'est pas ce que nous construisons, mais ce qui fonctionne encore cinq ans plus tard. »</blockquote>
        <h3>Et ensuite ?</h3>
        <ul>
            <li>Formation d'un comité de gestion local et d'un technicien de maintenance ;</li>
            <li>Mise en place d'une redevance d'usage symbolique pour financer les réparations ;</li>
            <li>Deux visites de suivi par an pendant trois ans ;</li>
            <li>Publication des résultats dans notre rapport annuel.</li>
        </ul>
        <p>Vous pouvez soutenir ces programmes par un don ponctuel ou régulier. Chaque contribution est affectée au projet que vous choisissez, et vous recevez un bilan détaillé à sa clôture.</p>
        HTML;
    }

    private function seedComments(): void
    {
        $comments = [
            ['Martine Aubert', 'martine.aubert@example.fr', 'Merci pour ce récit très concret, on comprend enfin où va l\'argent.', 'approved'],
            ['Olivier Ruiz', 'olivier.ruiz@example.fr', 'Serait-il possible de visiter un chantier en tant que donateur ?', 'approved'],
            ['Leïla Benhamou', 'leila.benhamou@example.fr', 'Je viens de mettre en place un don mensuel après avoir lu cet article.', 'approved'],
            ['Anonyme', 'spam@example.com', 'Super site, visitez le mien pour gagner de l\'argent facilement.', 'spam'],
            ['Grégoire Masson', 'gregoire.masson@example.fr', 'Avez-vous des chiffres sur le taux de panne des forages à cinq ans ?', 'pending'],
        ];

        foreach (Post::take(4)->get() as $post) {
            if ($post->comments()->exists()) {
                continue;
            }

            foreach (array_slice($comments, 0, random_int(2, 5)) as $index => [$name, $email, $body, $status]) {
                $post->comments()->create([
                    'name' => $name,
                    'email' => $email,
                    'body' => $body,
                    'status' => $status,
                    'created_at' => $post->published_at->addDays($index + 1),
                ]);
            }
        }
    }
}
