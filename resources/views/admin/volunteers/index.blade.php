@extends('layouts.admin')

@section('title', 'Bénévoles')
@section('subtitle', 'Candidatures reçues depuis le site')

@section('content')

    <x-admin.page-header title="Candidatures de bénévoles"
        :subtitle="($counts['pending'] ?? 0).' en attente, '.($counts['approved'] ?? 0).' accepté(s), '.($counts['rejected'] ?? 0).' refusé(s)'" />

    <x-admin.filters :action="route('admin.volunteers.index')" placeholder="Nom ou e-mail…" :selects="[
        'status' => ['label' => 'Statut', 'options' => \App\Models\Volunteer::STATUSES],
        'interest' => ['label' => 'Domaine', 'options' => \App\Models\Volunteer::INTERESTS],
    ]" />

    <x-admin.card :padded="false">
        @if ($volunteers->isEmpty())
            <x-admin.empty title="Aucune candidature" message="Les candidatures déposées sur le site apparaîtront ici." />
        @else
            <x-admin.table :head="['Candidat', 'Domaine', 'Disponibilités', 'Statut', 'Reçue le', 'Actions' => 'right']">
                @foreach ($volunteers as $volunteer)
                    <tr class="hover:bg-zinc-50">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.volunteers.show', $volunteer) }}"
                                class="font-medium text-zinc-900 hover:underline">{{ $volunteer->name }}</a>
                            <p class="text-xs text-zinc-500">{{ $volunteer->email }}</p>
                        </td>
                        <td class="px-5 py-3 text-zinc-600">{{ $volunteer->interest_label }}</td>
                        <td class="px-5 py-3 text-zinc-600">{{ $volunteer->availability ?: '—' }}</td>
                        <td class="px-5 py-3">
                            <x-admin.status-form :action="route('admin.volunteers.update', $volunteer)"
                                :options="\App\Models\Volunteer::STATUSES" :current="$volunteer->status" />
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-zinc-500">{{ $volunteer->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <x-admin.row-actions :view="route('admin.volunteers.show', $volunteer)"
                                :destroy="route('admin.volunteers.destroy', $volunteer)"
                                confirm="Supprimer la candidature de {{ $volunteer->name }} ?" />
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-admin.card>

    <x-admin.pagination :paginator="$volunteers" label="candidature" />

@endsection
