<?php

use App\Models\Category;
use App\Models\Cause;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
});

it('crée une cause avec une image et un slug automatique', function () {
    Storage::fake('public');

    $category = Category::factory()->create();

    $this->post(route('admin.causes.store'), [
        'title' => 'Un forage pour Boundiali',
        'category_id' => $category->id,
        'excerpt' => 'Les habitants marchent 6 km pour puiser de l\'eau.',
        'description' => '<p>Contexte du projet.</p>',
        'goal_amount' => 24000,
        'status' => 'published',
        'is_featured' => 1,
        'image' => UploadedFile::fake()->image('forage.jpg'),
    ])->assertRedirect(route('admin.causes.index'))->assertSessionHas('success');

    $cause = Cause::firstWhere('title', 'Un forage pour Boundiali');

    expect($cause->slug)->toBe('un-forage-pour-boundiali')
        ->and($cause->is_featured)->toBeTrue()
        ->and($cause->image)->toStartWith('uploads/causes/');

    Storage::disk('public')->assertExists($cause->image);
});

it('met à jour une cause et supprime l\'ancienne image à la demande', function () {
    Storage::fake('public');

    $cause = Cause::factory()->create();
    $cause->update(['image' => UploadedFile::fake()->image('old.jpg')->store('uploads/causes', 'public')]);
    $oldImage = $cause->image;

    $this->put(route('admin.causes.update', $cause), [
        'title' => 'Titre corrigé',
        'goal_amount' => 30000,
        'status' => 'closed',
        'remove_image' => 1,
    ])->assertRedirect(route('admin.causes.index'));

    expect($cause->fresh()->title)->toBe('Titre corrigé')
        ->and($cause->fresh()->status)->toBe('closed')
        ->and($cause->fresh()->image)->toBeNull();

    Storage::disk('public')->assertMissing($oldImage);
});

it('refuse une cause sans titre ni objectif', function () {
    $this->post(route('admin.causes.store'), ['status' => 'published'])
        ->assertSessionHasErrors(['title', 'goal_amount']);

    expect(Cause::count())->toBe(0);
});

it('supprime une cause en conservant ses dons', function () {
    $cause = Cause::factory()->create();
    $donation = Donation::factory()->for($cause)->create();

    $this->delete(route('admin.causes.destroy', $cause))->assertRedirect(route('admin.causes.index'));

    expect(Cause::count())->toBe(0)
        ->and($donation->fresh())->not->toBeNull()
        ->and($donation->fresh()->cause_id)->toBeNull();
});

it('valide un don et met à jour la collecte de la cause', function () {
    $cause = Cause::factory()->create(['goal_amount' => 1000]);
    $donation = Donation::factory()->for($cause)->pending()->create(['amount' => 250]);

    expect((float) $cause->fresh()->raised_amount)->toBe(0.0);

    $this->patch(route('admin.donations.status', $donation), ['status' => 'completed'])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($donation->fresh()->status)->toBe('completed')
        ->and($donation->fresh()->paid_at)->not->toBeNull()
        ->and((float) $cause->fresh()->raised_amount)->toBe(250.0);
});

it('enregistre un don saisi manuellement', function () {
    $this->post(route('admin.donations.store'), [
        'donor_name' => 'Entreprise Lumen',
        'donor_email' => 'mecenat@lumen.example',
        'amount' => 5000,
        'frequency' => 'yearly',
        'payment_method' => 'transfer',
        'status' => 'completed',
    ])->assertRedirect()->assertSessionHas('success');

    $donation = Donation::firstWhere('donor_email', 'mecenat@lumen.example');

    expect($donation->paid_at)->not->toBeNull()
        ->and($donation->reference)->toStartWith('DON-');
});

it('exporte les dons filtrés en CSV', function () {
    Donation::factory()->create(['donor_name' => 'Claire Fontaine', 'amount' => 120]);
    Donation::factory()->pending()->create(['donor_name' => 'Hugo Bertrand']);

    $response = $this->get(route('admin.donations.export', ['status' => 'completed']));

    $response->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');

    $csv = $response->streamedContent();

    expect($csv)->toContain('Claire Fontaine')
        ->and($csv)->not->toContain('Hugo Bertrand');
});

it('retrouve un don à partir du numéro lu sur le SMS Mobile Money', function () {
    Donation::factory()->create(['donor_name' => 'Awa Koné', 'donor_phone' => '07 12 34 56 78']);
    Donation::factory()->create(['donor_name' => 'Hugo Bertrand', 'donor_phone' => '05 99 88 77 66']);

    $csv = $this->get(route('admin.donations.export', ['q' => '+225 0712345678']))->streamedContent();

    expect($csv)->toContain('Awa Koné')
        ->and($csv)->not->toContain('Hugo Bertrand');
});

it('crée un article avec ses mots-clés', function () {
    $this->post(route('admin.posts.store'), [
        'title' => 'Nouvelles du terrain',
        'body' => '<p>Un récit de mission.</p>',
        'tags' => 'eau, terrain , eau',
        'status' => 'published',
        'allow_comments' => 1,
    ])->assertRedirect(route('admin.posts.index'));

    $post = Post::firstWhere('title', 'Nouvelles du terrain');

    expect($post->tags)->toBe(['eau', 'terrain'])
        ->and($post->user_id)->toBe($this->admin->id)
        ->and($post->published_at)->not->toBeNull();
});

it('ajoute des photos, des vidéos et des liens YouTube à la galerie d\'un article', function () {
    Storage::fake('public');
    $post = Post::factory()->create();

    $this->put(route('admin.posts.update', $post), [
        'title' => $post->title,
        'status' => 'published',
        'gallery_files' => [
            UploadedFile::fake()->image('puits.jpg'),
            UploadedFile::fake()->create('reportage.mp4', 500, 'video/mp4'),
        ],
        'gallery_videos' => "https://youtu.be/dQw4w9WgXcQ\nhttps://vimeo.com/76979871",
    ])->assertRedirect(route('admin.posts.index'));

    $gallery = $post->gallery()->get();

    expect($gallery)->toHaveCount(4)
        ->and($gallery->pluck('type')->all())->toBe(['image', 'video', 'video', 'video'])
        ->and($gallery[2]->url)->toBe('https://www.youtube.com/watch?v=dQw4w9WgXcQ')
        ->and($gallery[2]->thumbnail)->toBe('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg');

    Storage::disk('public')->assertExists($gallery[0]->path);

    // Légende, puis retrait d'une image avec suppression du fichier.
    $this->put(route('admin.posts.update', $post), [
        'title' => $post->title,
        'status' => 'published',
        'gallery' => [
            $gallery[0]->id => ['remove' => 1],
            $gallery[1]->id => ['caption' => 'Inauguration du puits', 'sort_order' => 9],
        ],
    ]);

    Storage::disk('public')->assertMissing($gallery[0]->path);
    expect($post->gallery()->count())->toBe(3)
        ->and($gallery[1]->fresh()->caption)->toBe('Inauguration du puits')
        ->and($post->gallery()->get()->last()->id)->toBe($gallery[1]->id);
});

it('refuse un lien vidéo qui n\'est ni YouTube ni Vimeo', function () {
    $post = Post::factory()->create();

    $this->put(route('admin.posts.update', $post), [
        'title' => $post->title,
        'status' => 'published',
        'gallery_videos' => 'https://exemple.com/video',
    ])->assertSessionHasErrors('gallery_videos');

    expect(PostMedia::count())->toBe(0);
});

it('modère un commentaire', function () {
    $comment = Comment::factory()->for(Post::factory())->pending()->create();

    $this->patch(route('admin.comments.update', $comment), ['status' => 'approved'])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($comment->fresh()->status)->toBe('approved');
});

it('crée un événement et confirme une inscription', function () {
    $this->post(route('admin.events.store'), [
        'title' => 'Course solidaire',
        'starts_at' => now()->addDays(10)->format('Y-m-d H:i'),
        'location' => 'Paris',
        'capacity' => 200,
        'ticket_price' => 15,
        'status' => 'published',
    ])->assertRedirect(route('admin.events.index'));

    $event = Event::firstWhere('title', 'Course solidaire');
    expect($event->slug)->toBe('course-solidaire');

    $registration = EventRegistration::factory()->for($event)->create();

    $this->patch(route('admin.registrations.update', $registration), ['status' => 'confirmed'])
        ->assertRedirect();

    expect($registration->fresh()->status)->toBe('confirmed');
});

it('refuse un événement dont la fin précède le début', function () {
    $this->post(route('admin.events.store'), [
        'title' => 'Dates incohérentes',
        'starts_at' => now()->addDays(10)->format('Y-m-d H:i'),
        'ends_at' => now()->addDays(9)->format('Y-m-d H:i'),
        'status' => 'published',
    ])->assertSessionHasErrors('ends_at');
});

it('traite une candidature de bénévole', function () {
    $volunteer = Volunteer::factory()->create();

    $this->patch(route('admin.volunteers.update', $volunteer), ['status' => 'approved'])
        ->assertRedirect();

    expect($volunteer->fresh()->status)->toBe('approved');
});

it('marque un message comme lu à l\'ouverture', function () {
    $message = ContactMessage::factory()->create(['status' => 'new']);

    $this->get(route('admin.messages.show', $message))->assertOk();

    expect($message->fresh()->status)->toBe('read')
        ->and($message->fresh()->read_at)->not->toBeNull();
});

it('crée les autres contenus de la vitrine', function () {
    $this->post(route('admin.team.store'), [
        'name' => 'Clara Monteiro',
        'role' => 'Coordinatrice',
        'is_active' => 1,
    ])->assertRedirect(route('admin.team.index'));

    $this->post(route('admin.testimonials.store'), [
        'name' => 'Jeanne Morel',
        'content' => 'Je sais exactement où va mon don.',
        'is_active' => 1,
    ])->assertRedirect(route('admin.testimonials.index'));

    $this->post(route('admin.faqs.store'), [
        'question' => 'Mon don est-il déductible ?',
        'answer' => 'Oui, à hauteur de 66 %.',
        'is_active' => 1,
    ])->assertRedirect(route('admin.faqs.index'));

    $this->post(route('admin.pages.store'), [
        'title' => 'Mentions légales',
        'content' => '<p>Éditeur du site…</p>',
        'is_active' => 1,
    ])->assertRedirect(route('admin.pages.index'));

    expect(TeamMember::firstWhere('name', 'Clara Monteiro'))->not->toBeNull()
        ->and(Page::firstWhere('slug', 'mentions-legales'))->not->toBeNull();
});

it('enregistre les paramètres du site', function () {
    $this->put(route('admin.settings.update'), [
        'site_name' => 'SolidAction Europe',
        'contact_email' => 'bonjour@solidaction.org',
        'currency_symbol' => '€',
        'donation_amounts' => '10,20,50',
    ])->assertRedirect()->assertSessionHas('success');

    expect(Setting::read('site_name'))->toBe('SolidAction Europe')
        ->and(Setting::read('contact_email'))->toBe('bonjour@solidaction.org');

    // La valeur est bien reprise par le site public.
    $this->get('/')->assertOk()->assertSee('SolidAction Europe');
});

it('refuse un e-mail invalide dans les paramètres', function () {
    $this->put(route('admin.settings.update'), [
        'site_name' => 'Test',
        'contact_email' => 'pas-un-email',
    ])->assertSessionHasErrors('contact_email');
});

it('empêche un administrateur de supprimer son propre compte', function () {
    $this->delete(route('admin.users.destroy', $this->admin))
        ->assertRedirect()
        ->assertSessionHas('error');

    expect(User::find($this->admin->id))->not->toBeNull();
});

it('empêche la suppression du dernier administrateur', function () {
    $other = User::factory()->admin()->create();

    // Deux administrateurs : la suppression est permise.
    $this->actingAs($other)->delete(route('admin.users.destroy', $this->admin))->assertRedirect();
    expect(User::where('role', 'admin')->count())->toBe(1);

    // Le dernier ne peut plus être supprimé par un éditeur promu.
    $editor = User::factory()->admin()->create();
    $this->actingAs($editor)->delete(route('admin.users.destroy', $other))->assertRedirect();

    expect(User::where('role', 'admin')->count())->toBe(1);
});

it('empêche un administrateur de se retirer ses propres droits', function () {
    $this->put(route('admin.users.update', $this->admin), [
        'name' => $this->admin->name,
        'email' => $this->admin->email,
        'role' => 'editor',
        'is_active' => 0,
    ])->assertRedirect();

    expect($this->admin->fresh()->role)->toBe('admin')
        ->and($this->admin->fresh()->is_active)->toBeTrue();
});

it('crée un compte utilisateur déjà vérifié', function () {
    $this->post(route('admin.users.store'), [
        'name' => 'Nouvelle éditrice',
        'email' => 'editrice@solidaction.org',
        'password' => 'mot-de-passe-solide',
        'password_confirmation' => 'mot-de-passe-solide',
        'role' => 'editor',
        'is_active' => 1,
    ])->assertRedirect(route('admin.users.index'));

    $created = User::firstWhere('email', 'editrice@solidaction.org');

    expect($created->role)->toBe('editor')
        ->and($created->email_verified_at)->not->toBeNull();
});

it('renvoie vers le formulaire avec un message clair quand l\'envoi dépasse post_max_size', function () {
    $post = Post::factory()->create();
    $edit = route('admin.posts.edit', $post);

    $this->call('PUT', route('admin.posts.update', $post), [], [], [], [
        'CONTENT_LENGTH' => 10 * 1024 ** 3,
        'HTTP_REFERER' => $edit,
    ])->assertRedirect($edit.'?envoi=trop-volumineux');

    $this->get($edit.'?envoi=trop-volumineux')
        ->assertOk()
        ->assertSee('Les fichiers envoyés étaient trop volumineux');
});
