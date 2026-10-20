@extends('layouts.admin')

@section('title', 'Mon compte')
@section('subtitle', 'Informations personnelles et mot de passe')

@section('content')

    <x-admin.page-header title="Mon compte"
        :subtitle="'Connecté en tant que '.$user->role_label.' depuis le '.$user->created_at->translatedFormat('d F Y')" />

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- ============ Profil ============ --}}
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <x-admin.card title="Profil" subtitle="Ces informations ne sont pas publiées sur le site.">
                <div class="space-y-5">
                    <x-admin.field name="name" label="Nom et prénom" :model="$user" required />
                    <x-admin.field name="email" label="Adresse e-mail" type="email" :model="$user" required
                        help="Changer d'adresse demandera une nouvelle vérification." />
                    <x-admin.field name="phone" label="Téléphone" :model="$user" />
                    <x-admin.image-field name="avatar" label="Avatar" :current="$user->avatar" />

                    <div class="flex items-center gap-3 border-t border-zinc-100 pt-5">
                        <x-admin.button type="submit" icon="check">Enregistrer le profil</x-admin.button>
                    </div>
                </div>
            </x-admin.card>
        </form>

        {{-- ============ Mot de passe ============ --}}
        <div class="space-y-6">
            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <x-admin.card title="Mot de passe" subtitle="Huit caractères minimum.">
                    <div class="space-y-5">
                        <x-admin.field name="current_password" label="Mot de passe actuel" type="password" required
                            autocomplete="current-password" />
                        <x-admin.field name="password" label="Nouveau mot de passe" type="password" required
                            autocomplete="new-password" />
                        <x-admin.field name="password_confirmation" label="Confirmation" type="password" required
                            autocomplete="new-password" />

                        <div class="flex items-center gap-3 border-t border-zinc-100 pt-5">
                            <x-admin.button type="submit" icon="check">Changer le mot de passe</x-admin.button>
                        </div>
                    </div>
                </x-admin.card>
            </form>

            <x-admin.card title="Session">
                <p class="text-sm text-zinc-600">
                    Vous êtes connecté avec le rôle <strong>{{ $user->role_label }}</strong>.
                    @if ($user->isAdmin())
                        Vous avez accès à la gestion des utilisateurs et aux paramètres du site.
                    @else
                        La gestion des utilisateurs et les paramètres sont réservés aux administrateurs.
                    @endif
                </p>

                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <x-admin.button type="submit" variant="secondary">Se déconnecter</x-admin.button>
                </form>
            </x-admin.card>
        </div>
    </div>

@endsection
