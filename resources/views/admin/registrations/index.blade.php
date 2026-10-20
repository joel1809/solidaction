@extends('layouts.admin')

@section('title', 'Inscriptions')
@section('subtitle', 'Participants inscrits aux événements')

@section('content')

    <x-admin.page-header title="Inscriptions aux événements" :subtitle="$registrations->total().' inscription(s)'">
        <x-admin.button variant="secondary" :href="route('admin.events.index')" icon="calendar">Voir les événements</x-admin.button>
    </x-admin.page-header>

    <x-admin.filters :action="route('admin.registrations.index')" placeholder="Nom ou e-mail…" :selects="[
        'event' => ['label' => 'Événement', 'options' => $events->all(), 'placeholder' => 'Tous'],
        'status' => ['label' => 'Statut', 'options' => \App\Models\EventRegistration::STATUSES],
    ]" />

    <x-admin.card :padded="false">
        @if ($registrations->isEmpty())
            <x-admin.empty title="Aucune inscription" message="Les inscriptions enregistrées depuis le site apparaîtront ici." />
        @else
            <x-admin.table :head="['Participant', 'Événement', 'Places' => 'center', 'Statut', 'Reçue le', 'Actions' => 'right']">
                @foreach ($registrations as $registration)
                    <tr class="hover:bg-zinc-50">
                        <td class="px-5 py-3">
                            <p class="font-medium text-zinc-900">{{ $registration->name }}</p>
                            <p class="text-xs text-zinc-500">{{ $registration->email }}</p>
                            @if ($registration->phone)
                                <p class="text-xs text-zinc-500">{{ $registration->phone }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if ($registration->event)
                                <a href="{{ route('admin.events.edit', $registration->event) }}"
                                    class="text-zinc-800 hover:underline">{{ Str::limit($registration->event->title, 32) }}</a>
                                <p class="text-xs text-zinc-500">{{ $registration->event->starts_at->translatedFormat('d M Y') }}</p>
                            @else
                                <span class="text-zinc-400">Événement supprimé</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center tabular-nums text-zinc-600">{{ $registration->seats }}</td>
                        <td class="px-5 py-3">
                            <x-admin.status-form :action="route('admin.registrations.update', $registration)"
                                :options="\App\Models\EventRegistration::STATUSES" :current="$registration->status" />
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-zinc-500">{{ $registration->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <x-admin.delete-form :action="route('admin.registrations.destroy', $registration)"
                                confirm="Supprimer l'inscription de {{ $registration->name }} ?" compact />
                        </td>
                    </tr>
                    @if ($registration->message)
                        <tr class="bg-zinc-50/60">
                            <td colspan="6" class="px-5 pb-3 text-xs text-zinc-600">
                                <span class="font-medium">Message :</span> {{ $registration->message }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </x-admin.table>
        @endif
    </x-admin.card>

    <x-admin.pagination :paginator="$registrations" label="inscription" />

@endsection
