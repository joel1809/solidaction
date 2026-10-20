@extends('layouts.admin')

@section('title', 'Abonnés')
@section('subtitle', 'Lettre d\'information')

@section('content')

    <x-admin.page-header title="Abonnés à la newsletter" :subtitle="$subscribers->total().' adresse(s) enregistrée(s)'">
        <x-admin.button variant="secondary" icon="download" :href="route('admin.subscribers.export')">
            Exporter en CSV
        </x-admin.button>
    </x-admin.page-header>

    <div class="mb-6 grid gap-4 sm:grid-cols-2">
        <x-admin.stat label="Abonnés actifs" :value="number_format($total, 0, ',', ' ')" icon="inbox" />
        <x-admin.stat label="Nouveaux ce mois-ci" :value="number_format($thisMonth, 0, ',', ' ')" icon="trend" />
    </div>

    <x-admin.filters :action="route('admin.subscribers.index')" placeholder="Adresse e-mail…" />

    <x-admin.card :padded="false">
        @if ($subscribers->isEmpty())
            <x-admin.empty title="Aucun abonné" message="Les inscriptions depuis le pied de page du site apparaîtront ici." />
        @else
            <x-admin.table :head="['Adresse e-mail', 'Inscrit le', 'Origine', 'Actions' => 'right']">
                @foreach ($subscribers as $subscriber)
                    <tr class="hover:bg-zinc-50">
                        <td class="px-5 py-3">
                            <a href="mailto:{{ $subscriber->email }}" class="font-medium text-zinc-900 hover:underline">
                                {{ $subscriber->email }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-zinc-500">{{ $subscriber->created_at->translatedFormat('d F Y') }}</td>
                        <td class="px-5 py-3 font-mono text-xs text-zinc-500">{{ $subscriber->ip_address ?: '—' }}</td>
                        <td class="px-5 py-3">
                            <x-admin.delete-form :action="route('admin.subscribers.destroy', $subscriber)"
                                confirm="Désinscrire {{ $subscriber->email }} ?" compact />
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-admin.card>

    <x-admin.pagination :paginator="$subscribers" label="abonné" />

@endsection
