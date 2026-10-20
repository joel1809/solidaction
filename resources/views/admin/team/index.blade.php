@extends('layouts.admin')

@section('title', 'Équipe')
@section('subtitle', 'Membres présentés sur la page Notre équipe')

@section('content')

    <x-admin.page-header title="Équipe" :subtitle="$members->total().' membre(s)'">
        <x-admin.button icon="plus" :href="route('admin.team.create')">Ajouter un membre</x-admin.button>
    </x-admin.page-header>

    <x-admin.filters :action="route('admin.team.index')" placeholder="Nom du membre…" />

    @if ($members->isEmpty())
        <x-admin.card :padded="false">
            <x-admin.empty title="Aucun membre" message="Présentez les personnes qui font vivre l'association.">
                <x-admin.button icon="plus" :href="route('admin.team.create')">Ajouter un membre</x-admin.button>
            </x-admin.empty>
        </x-admin.card>
    @else
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($members as $member)
                <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
                    <img src="{{ media($member->photo, 'theme/assets/images/team/team-v1-1.jpg') }}" alt="{{ $member->name }}"
                        class="aspect-[4/5] w-full object-cover">
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="truncate font-medium text-zinc-900">{{ $member->name }}</p>
                                <p class="truncate text-xs text-zinc-500">{{ $member->role }}</p>
                            </div>
                            <x-admin.badge :color="$member->is_active ? 'green' : 'zinc'">
                                {{ $member->is_active ? 'Visible' : 'Masqué' }}
                            </x-admin.badge>
                        </div>

                        @if ($member->bio)
                            <p class="mt-2 line-clamp-2 text-xs text-zinc-500">{{ $member->bio }}</p>
                        @endif

                        <div class="mt-3 flex items-center justify-between border-t border-zinc-100 pt-3">
                            <span class="text-xs text-zinc-400">Ordre {{ $member->sort_order }}</span>
                            <x-admin.row-actions :edit="route('admin.team.edit', $member)"
                                :destroy="route('admin.team.destroy', $member)"
                                confirm="Retirer {{ $member->name }} de l'équipe ?" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <x-admin.pagination :paginator="$members" label="membre" />

@endsection
