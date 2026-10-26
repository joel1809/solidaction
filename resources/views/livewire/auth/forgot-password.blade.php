<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Mot de passe oublié" description="Saisissez votre e-mail pour recevoir un lien de réinitialisation" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <div class="grid gap-2">
            <flux:input wire:model="email" label="{{ __('Email Address') }}" type="email" name="email" required autofocus placeholder="prenom.nom@exemple.fr" />
        </div>

        <flux:button variant="primary" type="submit" class="w-full hover:!bg-brand-green-dark">{{ __('Email password reset link') }}</flux:button>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600">
        Ou revenir à la page de
        <x-text-link class="!text-brand-green decoration-brand-green/40 hover:decoration-brand-green" href="{{ route('login') }}">connexion</x-text-link>
    </div>
</div>
