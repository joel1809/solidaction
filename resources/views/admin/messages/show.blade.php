@extends('layouts.admin')

@section('title', $message->subject ?: 'Message de '.$message->name)

@section('content')

    <x-admin.page-header :title="$message->subject ?: 'Sans objet'"
        :subtitle="'Reçu le '.$message->created_at->translatedFormat('d F Y à H\hi').' de '.$message->name">
        <x-admin.button variant="secondary" :href="route('admin.messages.index')">Retour à la boîte</x-admin.button>
        <x-admin.button variant="secondary" icon="mail"
            :href="'mailto:'.$message->email.'?subject='.rawurlencode('Re : '.($message->subject ?: 'Votre message'))">
            Répondre par e-mail
        </x-admin.button>
    </x-admin.page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Message">
                <p class="whitespace-pre-line text-sm leading-relaxed text-zinc-700">{{ $message->message }}</p>
            </x-admin.card>

            <x-admin.card title="Note de suivi" subtitle="Conservez une trace interne de la réponse apportée.">
                <form action="{{ route('admin.messages.update', $message) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <x-admin.field name="reply" label="Réponse / note interne" type="textarea" rows="6"
                        :model="$message" help="Ce texte n'est pas envoyé automatiquement : il sert d'historique." />

                    <x-admin.field name="status" label="Statut" type="select" :value="$message->status"
                        :options="\App\Models\ContactMessage::STATUSES" required />

                    <x-admin.button type="submit" icon="check">Enregistrer</x-admin.button>
                </form>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Expéditeur">
                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">Nom</dt>
                        <dd class="mt-0.5 font-medium text-zinc-900">{{ $message->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">E-mail</dt>
                        <dd class="mt-0.5"><a href="mailto:{{ $message->email }}" class="text-zinc-800 hover:underline">{{ $message->email }}</a></dd>
                    </div>
                    @if ($message->phone)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-zinc-500">Téléphone</dt>
                            <dd class="mt-0.5 text-zinc-800">{{ $message->phone }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">Lu le</dt>
                        <dd class="mt-0.5 text-zinc-800">{{ $message->read_at?->translatedFormat('d F Y à H\hi') ?? 'Non lu' }}</dd>
                    </div>
                </dl>
            </x-admin.card>

            <x-admin.card title="Zone sensible">
                <x-admin.delete-form :action="route('admin.messages.destroy', $message)"
                    label="Supprimer le message" confirm="Supprimer définitivement ce message ?" />
            </x-admin.card>
        </div>
    </div>

@endsection
