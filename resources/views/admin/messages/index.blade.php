@extends('layouts.admin')

@section('title', 'Messages')
@section('subtitle', 'Demandes reçues via le formulaire de contact')

@section('content')

    <x-admin.page-header title="Messages de contact"
        :subtitle="($counts['new'] ?? 0).' non lu(s), '.($counts['read'] ?? 0).' lu(s), '.($counts['replied'] ?? 0).' traité(s)'" />

    <x-admin.filters :action="route('admin.messages.index')" placeholder="Nom, e-mail ou objet…" :selects="[
        'status' => ['label' => 'Statut', 'options' => \App\Models\ContactMessage::STATUSES],
    ]" />

    <x-admin.card :padded="false">
        @if ($messages->isEmpty())
            <x-admin.empty title="Aucun message" message="La boîte de réception est vide." />
        @else
            <ul class="divide-y divide-zinc-100">
                @foreach ($messages as $message)
                    <li class="flex flex-wrap items-start gap-4 p-5 hover:bg-zinc-50 {{ $message->status === 'new' ? 'bg-amber-50/40' : '' }}">
                        <span class="grid size-10 shrink-0 place-items-center rounded-full bg-zinc-900 text-xs font-semibold text-white">
                            {{ Str::upper(Str::substr($message->name, 0, 2)) }}
                        </span>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('admin.messages.show', $message) }}"
                                    class="font-medium text-zinc-900 hover:underline">{{ $message->subject ?: 'Sans objet' }}</a>
                                <x-admin.badge :color="['new' => 'amber', 'read' => 'blue', 'replied' => 'green', 'archived' => 'zinc'][$message->status]">
                                    {{ $message->status_label }}
                                </x-admin.badge>
                            </div>
                            <p class="mt-0.5 text-xs text-zinc-500">
                                {{ $message->name }} &middot; {{ $message->email }}
                                @if ($message->phone) &middot; {{ $message->phone }} @endif
                            </p>
                            <p class="mt-2 text-sm text-zinc-600">{{ Str::limit($message->message, 160) }}</p>
                        </div>

                        <div class="flex shrink-0 flex-col items-end gap-2">
                            <span class="text-xs text-zinc-500">{{ $message->created_at->translatedFormat('d M Y') }}</span>
                            <x-admin.row-actions :view="route('admin.messages.show', $message)"
                                :destroy="route('admin.messages.destroy', $message)"
                                confirm="Supprimer ce message ?" />
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-admin.card>

    <x-admin.pagination :paginator="$messages" label="message" />

@endsection
