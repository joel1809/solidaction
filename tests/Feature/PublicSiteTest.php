<?php

use App\Models\Category;
use App\Models\Cause;
use App\Models\Event;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Slider;
use App\Models\TeamMember;
use App\Models\Testimonial;

beforeEach(function () {
    Slider::factory()->create();
    Partner::factory()->create();
    TeamMember::factory()->count(2)->create();
    Testimonial::factory()->count(2)->create();
    Faq::factory()->count(3)->create();
});

it('affiche la page d\'accueil avec les contenus publiés', function () {
    $cause = Cause::factory()->featured()->create(['title' => 'Un forage à Boundiali']);
    $post = Post::factory()->create(['title' => 'Nouvelles du terrain']);
    Event::factory()->create(['title' => 'Course solidaire']);

    $this->get('/')
        ->assertOk()
        ->assertSee($cause->title)
        ->assertSee($post->title)
        ->assertSee('Course solidaire');
});

it('liste les causes et filtre par programme', function () {
    $category = Category::factory()->create(['name' => 'Eau potable', 'slug' => 'eau-potable']);
    $matching = Cause::factory()->create(['category_id' => $category->id, 'title' => 'Forage de Kolda']);
    $other = Cause::factory()->create(['title' => 'École de Dosso']);

    $this->get('/causes')->assertOk()->assertSee($matching->title)->assertSee($other->title);

    // Le pied de page listant toutes les causes récentes, on vérifie la collection filtrée.
    $this->get('/causes?categorie=eau-potable')
        ->assertOk()
        ->assertSee($matching->title)
        ->assertViewHas('causes', fn ($causes) => $causes->pluck('id')->all() === [$matching->id]);
});

it('cache les causes en brouillon', function () {
    $draft = Cause::factory()->draft()->create();

    $this->get('/causes')->assertOk()->assertDontSee($draft->title);
    $this->get('/causes/'.$draft->slug)->assertNotFound();
});

it('affiche le détail d\'une cause et incrémente les vues', function () {
    $cause = Cause::factory()->create(['views' => 0]);

    $this->get('/causes/'.$cause->slug)->assertOk()->assertSee($cause->title);

    expect($cause->fresh()->views)->toBe(1);
});

it('sépare les événements à venir des événements passés', function () {
    $upcoming = Event::factory()->create(['title' => 'Dîner caritatif']);
    $past = Event::factory()->past()->create(['title' => 'Assemblée générale']);

    $this->get('/evenements')->assertOk()->assertSee($upcoming->title)->assertDontSee($past->title);
    $this->get('/evenements?periode=past')->assertOk()->assertSee($past->title)->assertDontSee($upcoming->title);
});

it('affiche les actualités en grille et en liste', function () {
    $post = Post::factory()->create();
    Post::factory()->draft()->create(['title' => 'Brouillon invisible']);

    $this->get('/actualites')->assertOk()->assertSee($post->title)->assertDontSee('Brouillon invisible');
    $this->get('/actualites?vue=liste')->assertOk()->assertSee($post->title);
    $this->get('/actualites/'.$post->slug)->assertOk()->assertSee($post->title);
});

it('rend les pages institutionnelles', function () {
    $page = Page::factory()->create(['slug' => 'mentions-legales']);

    collect(['/a-propos', '/equipe', '/temoignages', '/faq', '/contact', '/devenir-benevole', '/faire-un-don'])
        ->each(fn (string $url) => $this->get($url)->assertOk());

    $this->get('/page/mentions-legales')->assertOk()->assertSee($page->title);
});

it('renvoie une 404 pour une page désactivée', function () {
    $page = Page::factory()->create(['is_active' => false]);

    $this->get('/page/'.$page->slug)->assertNotFound();
});
