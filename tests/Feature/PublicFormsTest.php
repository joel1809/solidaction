<?php

use App\Models\Cause;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Post;
use App\Models\Subscriber;
use App\Models\Volunteer;

it('enregistre un don et redirige vers la page de remerciement', function () {
    $cause = Cause::factory()->create();

    $response = $this->post('/faire-un-don', [
        'cause_id' => $cause->id,
        'donor_name' => 'Claire Fontaine',
        'donor_email' => 'claire@example.fr',
        'amount' => 120,
        'donor_phone' => '07 12 34 56 78',
        'payment_method' => 'orange_money',
        'message' => 'Courage à toute l\'équipe !',
    ]);

    $donation = Donation::firstWhere('donor_email', 'claire@example.fr');

    expect($donation)->not->toBeNull()
        ->and($donation->status)->toBe('pending')
        ->and($donation->reference)->toStartWith('DON-');

    $response->assertRedirect(route('donations.thanks', $donation->reference));

    $this->get(route('donations.thanks', $donation->reference))
        ->assertOk()
        ->assertSee($donation->reference);
});

it('cache la page de remerciement d\'un don à un autre visiteur', function () {
    $donation = Donation::factory()->create();

    $this->get(route('donations.thanks', $donation->reference))->assertNotFound();
});

it('limite les envois en rafale des formulaires publics', function () {
    foreach (range(1, 5) as $i) {
        $this->post('/newsletter', ['email' => "abonne{$i}@example.fr"])->assertRedirect();
    }

    $this->post('/newsletter', ['email' => 'abonne6@example.fr'])->assertTooManyRequests();
});

it('retire les scripts du contenu riche des articles', function () {
    $post = Post::factory()->create([
        'status' => 'published',
        'published_at' => now()->subDay(),
        'body' => '<p>Bonjour<script>alert(1)</script></p><a href="javascript:alert(2)">lien</a>',
    ]);

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee('Bonjour')
        ->assertDontSee('alert(1)', false)
        ->assertDontSee('javascript:alert(2)', false);
});

it('refuse un don sans montant valide', function () {
    $this->post('/faire-un-don', [
        'donor_name' => 'Test',
        'donor_email' => 'test@example.fr',
        'amount' => 0,
        'payment_method' => 'card',
    ])->assertSessionHasErrors('amount');

    expect(Donation::count())->toBe(0);
});

it('exige le numéro du payeur pour un don par Mobile Money', function () {
    $this->post('/faire-un-don', [
        'donor_name' => 'Awa Koné',
        'donor_email' => 'awa@example.ci',
        'amount' => 5000,
        'payment_method' => 'orange_money',
    ])->assertSessionHasErrors('donor_phone');

    expect(Donation::count())->toBe(0);
});

it('recalcule le montant collecté d\'une cause sur les dons validés', function () {
    $cause = Cause::factory()->create(['goal_amount' => 1000]);

    Donation::factory()->for($cause)->create(['amount' => 300]);
    Donation::factory()->for($cause)->create(['amount' => 200]);
    Donation::factory()->for($cause)->pending()->create(['amount' => 500]);

    expect((float) $cause->fresh()->raised_amount)->toBe(500.0)
        ->and($cause->fresh()->progress)->toBe(50);
});

it('enregistre une inscription à un événement', function () {
    $event = Event::factory()->create();

    $this->post(route('events.register', $event), [
        'name' => 'Samir Oualid',
        'email' => 'samir@example.fr',
        'seats' => 2,
    ])->assertRedirect()->assertSessionHas('success');

    expect($event->registrations()->count())->toBe(1)
        ->and($event->fresh()->seats_taken)->toBe(2);
});

it('bloque une inscription qui dépasse la capacité restante', function () {
    $event = Event::factory()->full()->create();

    $this->post(route('events.register', $event), [
        'name' => 'Trop de monde',
        'email' => 'trop@example.fr',
        'seats' => 5,
    ])->assertSessionHasErrors('seats');

    expect($event->registrations()->count())->toBe(0);
});

it('enregistre un commentaire en attente de modération', function () {
    $post = Post::factory()->create();

    $this->post(route('posts.comment', $post), [
        'name' => 'Martine Aubert',
        'email' => 'martine@example.fr',
        'body' => 'Merci pour ce récit très concret.',
    ])->assertRedirect()->assertSessionHas('success');

    $comment = $post->comments()->first();

    expect($comment->status)->toBe('pending');

    // Tant qu'il n'est pas approuvé, le commentaire reste invisible.
    $this->get(route('posts.show', $post))->assertDontSee('Merci pour ce récit très concret.');
});

it('refuse un commentaire sur un article qui les a désactivés', function () {
    $post = Post::factory()->create(['allow_comments' => false]);

    $this->post(route('posts.comment', $post), [
        'name' => 'Spam',
        'email' => 'spam@example.com',
        'body' => 'Message indésirable',
    ])->assertForbidden();
});

it('enregistre un message de contact', function () {
    $this->post('/contact', [
        'name' => 'Brigitte Lenoir',
        'email' => 'brigitte@example.fr',
        'subject' => 'Legs et donation',
        'message' => 'Je souhaite inclure votre association dans mon testament.',
    ])->assertRedirect(route('contact.create'))->assertSessionHas('success');

    expect(ContactMessage::where('email', 'brigitte@example.fr')->first()->status)->toBe('new');
});

it('enregistre une candidature de bénévole', function () {
    $this->post('/devenir-benevole', [
        'name' => 'Léa Fournier',
        'email' => 'lea@example.fr',
        'interest' => 'communication',
        'availability' => 'Deux soirs par semaine',
    ])->assertRedirect()->assertSessionHas('success');

    expect(Volunteer::where('email', 'lea@example.fr')->first()->status)->toBe('pending');
});

it('refuse une candidature avec un domaine inconnu', function () {
    $this->post('/devenir-benevole', [
        'name' => 'Test',
        'email' => 'test@example.fr',
        'interest' => 'pilotage-de-drone',
    ])->assertSessionHasErrors('interest');
});

it('inscrit un e-mail à la newsletter sans doublon', function () {
    $this->post('/newsletter', ['email' => 'abonne@example.fr'])
        ->assertRedirect()
        ->assertSessionHas('newsletter_success');

    $this->post('/newsletter', ['email' => 'abonne@example.fr'])->assertRedirect();

    expect(Subscriber::where('email', 'abonne@example.fr')->count())->toBe(1);
});
