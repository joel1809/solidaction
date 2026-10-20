<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get('/admin')->assertRedirect('/login');
});

test('the legacy dashboard url points at the administration', function () {
    $this->get('/dashboard')->assertRedirect('/admin');
});

test('staff members can visit the administration dashboard', function () {
    $this->actingAs(User::factory()->editor()->create());

    $this->get('/admin')->assertOk()->assertSee('Tableau de bord');
});

test('users without a staff role cannot visit the dashboard', function () {
    $this->actingAs(User::factory()->visitor()->create());

    $this->get('/admin')->assertForbidden();
});

test('non staff accounts are redirected to the public site after login', function () {
    $this->actingAs(User::factory()->visitor()->create());

    $this->get('/dashboard')->assertRedirect(route('home'));
});
