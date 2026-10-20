<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->user = User::factory()->editor()->create(['password' => Hash::make('password')]);
    $this->actingAs($this->user);
});

it('affiche la page Mon compte', function () {
    $this->get(route('admin.profile.edit'))
        ->assertOk()
        ->assertSee('Mon compte')
        ->assertSee($this->user->email);
});

it('met à jour le profil et réinitialise la vérification en cas de changement d\'e-mail', function () {
    $this->put(route('admin.profile.update'), [
        'name' => 'Marc Lefèvre',
        'email' => 'marc@solidaction.org',
        'phone' => '+33 1 84 80 12 35',
    ])->assertRedirect()->assertSessionHas('success');

    $this->user->refresh();

    expect($this->user->name)->toBe('Marc Lefèvre')
        ->and($this->user->email)->toBe('marc@solidaction.org')
        ->and($this->user->email_verified_at)->toBeNull();
});

it('conserve la vérification quand l\'e-mail ne change pas', function () {
    $this->put(route('admin.profile.update'), [
        'name' => 'Nom modifié',
        'email' => $this->user->email,
    ])->assertRedirect();

    expect($this->user->fresh()->email_verified_at)->not->toBeNull();
});

it('change le mot de passe avec le mot de passe actuel', function () {
    $this->put(route('admin.profile.password'), [
        'current_password' => 'password',
        'password' => 'nouveau-mot-de-passe',
        'password_confirmation' => 'nouveau-mot-de-passe',
    ])->assertRedirect()->assertSessionHas('success');

    expect(Hash::check('nouveau-mot-de-passe', $this->user->fresh()->password))->toBeTrue();
});

it('refuse le changement de mot de passe si l\'actuel est faux', function () {
    $this->put(route('admin.profile.password'), [
        'current_password' => 'mauvais',
        'password' => 'nouveau-mot-de-passe',
        'password_confirmation' => 'nouveau-mot-de-passe',
    ])->assertSessionHasErrors('current_password');

    expect(Hash::check('password', $this->user->fresh()->password))->toBeTrue();
});
