@extends('layouts.admin')

@section('title', $user->exists ? 'Modifier un compte' : 'Nouveau compte')

@section('content')

    <x-admin.page-header :title="$user->exists ? 'Modifier le compte' : 'Nouveau compte'"
        :subtitle="$user->exists ? $user->email : 'Un e-mail et un mot de passe suffisent pour accéder à l\'administration.'">
        <x-admin.button variant="secondary" :href="route('admin.users.index')">Retour à la liste</x-admin.button>
    </x-admin.page-header>

    <form action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}"
        method="POST" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($user->exists)
            @method('PUT')
        @endif

        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Identité">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.field name="name" label="Nom et prénom" :model="$user" required />
                    <x-admin.field name="email" label="Adresse e-mail" type="email" :model="$user" required />
                    <x-admin.field name="phone" label="Téléphone" :model="$user" />
                </div>
            </x-admin.card>

            <x-admin.card title="Mot de passe"
                :subtitle="$user->exists ? 'Laissez les champs vides pour conserver le mot de passe actuel.' : 'Huit caractères minimum.'">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.field name="password" label="Mot de passe" type="password" :required="! $user->exists"
                        autocomplete="new-password" />
                    <x-admin.field name="password_confirmation" label="Confirmation" type="password"
                        :required="! $user->exists" autocomplete="new-password" />
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Droits">
                <div class="space-y-5">
                    @if ($user->exists && $user->is(auth()->user()))
                        <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">
                            Vous modifiez votre propre compte : le rôle et l'activation sont verrouillés pour éviter
                            de vous couper l'accès.
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-700">Rôle</p>
                            <p class="mt-1 text-sm text-zinc-500">{{ $user->role_label }}</p>
                        </div>
                    @else
                        <x-admin.field name="role" label="Rôle" type="select" :model="$user" required
                            :options="\App\Models\User::ROLES" />

                        <div class="rounded-lg bg-zinc-50 px-4 py-3 text-xs text-zinc-600">
                            <p><strong>Administrateur</strong> : accès complet, y compris utilisateurs et paramètres.</p>
                            <p class="mt-1"><strong>Éditeur</strong> : gère les contenus, les dons et les inscriptions.</p>
                        </div>

                        <x-admin.field name="is_active" type="checkbox" label="Compte actif"
                            :model="$user" help="Un compte désactivé ne peut plus accéder à l'administration." />
                    @endif
                </div>
            </x-admin.card>

            <x-admin.card title="Photo">
                <x-admin.image-field name="avatar" label="Avatar" :current="$user->avatar"
                    help="Carrée de préférence, 2 Mo maximum." />
            </x-admin.card>

            <div class="flex items-center gap-3">
                <x-admin.button type="submit" icon="check">{{ $user->exists ? 'Enregistrer' : 'Créer le compte' }}</x-admin.button>
                <x-admin.button variant="ghost" :href="route('admin.users.index')">Annuler</x-admin.button>
            </div>
        </div>
    </form>

@endsection
