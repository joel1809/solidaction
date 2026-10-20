<?php

use App\Models\Category;
use App\Models\Cause;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Slider;
use App\Models\Subscriber;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Volunteer;
use Livewire\Volt\Volt;

it('redirige les visiteurs anonymes vers la connexion', function () {
    $this->get('/admin')->assertRedirect('/login');
    $this->get('/dashboard')->assertRedirect('/admin');
});

it('refuse l\'accès aux comptes sans rôle d\'équipe', function () {
    $this->actingAs(User::factory()->visitor()->create())
        ->get('/admin')
        ->assertForbidden();
});

it('refuse l\'accès aux comptes désactivés', function () {
    $this->actingAs(User::factory()->admin()->create(['is_active' => false]))
        ->get('/admin')
        ->assertForbidden();
});

it('réserve les utilisateurs et les paramètres aux administrateurs', function () {
    $editor = User::factory()->editor()->create();

    $this->actingAs($editor)->get(route('admin.users.index'))->assertForbidden();
    $this->actingAs($editor)->get(route('admin.settings.edit'))->assertForbidden();

    // L'éditeur garde l'accès aux contenus.
    $this->actingAs($editor)->get(route('admin.posts.index'))->assertOk();
});

it('affiche le tableau de bord avec ses indicateurs', function () {
    $cause = Cause::factory()->create();
    Donation::factory()->for($cause)->count(3)->create(['amount' => 100]);
    Event::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->get('/admin')
        ->assertOk()
        ->assertSee('Tableau de bord')
        ->assertSee('Dons encaissés par mois')
        ->assertViewHas('kpis')
        ->assertViewHas('monthly', fn ($monthly) => count($monthly) === 12);
});

it('ouvre toutes les pages de liste de l\'administration', function () {
    // Au moins un enregistrement par module pour couvrir le rendu des tableaux.
    $cause = Cause::factory()->create();
    Donation::factory()->for($cause)->create();
    $post = Post::factory()->create();
    Comment::factory()->for($post)->create();
    $event = Event::factory()->create();
    EventRegistration::factory()->for($event)->create();
    TeamMember::factory()->create();
    Testimonial::factory()->create();
    Faq::factory()->create();
    Slider::factory()->create();
    Partner::factory()->create();
    Page::factory()->create();
    Volunteer::factory()->create();
    ContactMessage::factory()->create();
    Subscriber::factory()->create();

    $this->actingAs(User::factory()->admin()->create());

    collect([
        'admin.dashboard', 'admin.causes.index', 'admin.donations.index', 'admin.posts.index',
        'admin.comments.index', 'admin.categories.index', 'admin.pages.index', 'admin.events.index',
        'admin.registrations.index', 'admin.volunteers.index', 'admin.sliders.index', 'admin.team.index',
        'admin.testimonials.index', 'admin.faqs.index', 'admin.partners.index', 'admin.messages.index',
        'admin.subscribers.index', 'admin.users.index', 'admin.settings.edit',
    ])->each(fn (string $name) => $this->get(route($name))->assertOk());
});

it('ouvre tous les formulaires de création', function () {
    $this->actingAs(User::factory()->admin()->create());

    collect([
        'admin.causes.create', 'admin.donations.create', 'admin.posts.create', 'admin.categories.create',
        'admin.pages.create', 'admin.events.create', 'admin.sliders.create', 'admin.team.create',
        'admin.testimonials.create', 'admin.faqs.create', 'admin.partners.create', 'admin.users.create',
    ])->each(fn (string $name) => $this->get(route($name))->assertOk());
});

it('ouvre tous les formulaires de modification', function () {
    $this->actingAs(User::factory()->admin()->create());

    $cause = Cause::factory()->create();

    $targets = [
        'admin.causes.edit' => $cause,
        'admin.donations.edit' => Donation::factory()->for($cause)->create(),
        'admin.donations.show' => Donation::factory()->for($cause)->create(),
        'admin.posts.edit' => Post::factory()->create(),
        'admin.categories.edit' => Category::factory()->create(),
        'admin.pages.edit' => Page::factory()->create(),
        'admin.events.edit' => Event::factory()->create(),
        'admin.sliders.edit' => Slider::factory()->create(),
        'admin.team.edit' => TeamMember::factory()->create(),
        'admin.testimonials.edit' => Testimonial::factory()->create(),
        'admin.faqs.edit' => Faq::factory()->create(),
        'admin.partners.edit' => Partner::factory()->create(),
        'admin.volunteers.show' => Volunteer::factory()->create(),
        'admin.messages.show' => ContactMessage::factory()->create(),
        'admin.users.edit' => User::factory()->editor()->create(),
    ];

    foreach ($targets as $name => $model) {
        $this->get(route($name, $model))->assertOk();
    }
});

it('n\'accorde aucun accès à l\'administration lors d\'une inscription publique', function () {
    Volt::test('auth.register')
        ->set('name', 'Visiteur Curieux')
        ->set('email', 'curieux@example.fr')
        ->set('password', 'mot-de-passe-solide')
        ->set('password_confirmation', 'mot-de-passe-solide')
        ->call('register')
        ->assertHasNoErrors();

    $registered = User::firstWhere('email', 'curieux@example.fr');

    expect($registered)->not->toBeNull()
        ->and($registered->role)->toBe('visitor')
        ->and($registered->isStaff())->toBeFalse();

    $this->actingAs($registered)->get('/admin')->assertForbidden();
});
