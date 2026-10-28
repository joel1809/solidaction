@extends('layouts.admin')

@section('title', 'Partenaires')
@section('subtitle', 'Logos affichés en bas des pages')

@section('content')

    <x-admin.page-header title="Partenaires" :subtitle="$partners->total().' partenaire(s)'">
        <x-admin.button icon="plus" :href="route('admin.partners.create')">Nouveau partenaire</x-admin.button>
    </x-admin.page-header>

    @if ($partners->isEmpty())
        <x-admin.card :padded="false">
            <x-admin.empty title="Aucun partenaire" message="Ajoutez les logos de vos mécènes et institutions partenaires.">
                <x-admin.button icon="plus" :href="route('admin.partners.create')">Nouveau partenaire</x-admin.button>
            </x-admin.empty>
        </x-admin.card>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($partners as $partner)
                <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
                    <div class="h-20 overflow-hidden rounded-lg bg-zinc-50 p-3">
                        <img src="{{ media($partner->logo, 'theme/assets/images/brand/brand-logo-1.png') }}"
                            alt="{{ $partner->name }}" class="size-full object-contain">
                    </div>
                    <div class="mt-3 flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-zinc-900">{{ $partner->name }}</p>
                            <p class="truncate text-xs text-zinc-500">{{ $partner->url ?: 'Aucun lien' }}</p>
                        </div>
                        <x-admin.badge :color="$partner->is_active ? 'green' : 'zinc'">
                            {{ $partner->is_active ? 'Visible' : 'Masqué' }}
                        </x-admin.badge>
                    </div>
                    <div class="mt-3 flex items-center justify-between border-t border-zinc-100 pt-3">
                        <span class="text-xs text-zinc-400">Ordre {{ $partner->sort_order }}</span>
                        <x-admin.row-actions :edit="route('admin.partners.edit', $partner)"
                            :destroy="route('admin.partners.destroy', $partner)" />
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <x-admin.pagination :paginator="$partners" label="partenaire" />

@endsection
