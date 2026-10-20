@extends('layouts.admin')

@section('title', 'Candidature de '.$volunteer->name)

@section('content')

    <x-admin.page-header :title="$volunteer->name"
        :subtitle="'Candidature reçue le '.$volunteer->created_at->translatedFormat('d F Y à H\hi')">
        <x-admin.button variant="secondary" :href="route('admin.volunteers.index')">Retour à la liste</x-admin.button>
        <x-admin.button variant="secondary" :href="'mailto:'.$volunteer->email" icon="mail">Répondre par e-mail</x-admin.button>
    </x-admin.page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Motivation">
                @if ($volunteer->message)
                    <p class="whitespace-pre-line text-sm leading-relaxed text-zinc-700">{{ $volunteer->message }}</p>
                @else
                    <p class="text-sm text-zinc-500">Le candidat n'a pas laissé de message.</p>
                @endif
            </x-admin.card>

            <x-admin.card title="Informations">
                <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2 text-sm">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">E-mail</dt>
                        <dd class="mt-0.5"><a href="mailto:{{ $volunteer->email }}" class="text-zinc-800 hover:underline">{{ $volunteer->email }}</a></dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">Téléphone</dt>
                        <dd class="mt-0.5 text-zinc-800">{{ $volunteer->phone ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">Ville</dt>
                        <dd class="mt-0.5 text-zinc-800">{{ $volunteer->address ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">Date de naissance</dt>
                        <dd class="mt-0.5 text-zinc-800">
                            {{ $volunteer->birthdate?->translatedFormat('d F Y') ?? '—' }}
                            @if ($volunteer->birthdate)
                                <span class="text-zinc-500">({{ (int) $volunteer->birthdate->diffInYears(now()) }} ans)</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">Domaine souhaité</dt>
                        <dd class="mt-0.5 text-zinc-800">{{ $volunteer->interest_label }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">Disponibilités</dt>
                        <dd class="mt-0.5 text-zinc-800">{{ $volunteer->availability ?: '—' }}</dd>
                    </div>
                </dl>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Suivi de la candidature">
                <form action="{{ route('admin.volunteers.update', $volunteer) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <x-admin.field name="status" label="Statut" type="select" :value="$volunteer->status"
                        :options="\App\Models\Volunteer::STATUSES" required />
                    <x-admin.button type="submit" icon="check">Enregistrer</x-admin.button>
                </form>
            </x-admin.card>

            <x-admin.card title="Zone sensible">
                <p class="mb-3 text-sm text-zinc-500">Supprimez la candidature si elle n'est plus pertinente.</p>
                <x-admin.delete-form :action="route('admin.volunteers.destroy', $volunteer)"
                    label="Supprimer la candidature"
                    confirm="Supprimer définitivement la candidature de {{ $volunteer->name }} ?" />
            </x-admin.card>
        </div>
    </div>

@endsection
