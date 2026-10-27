@extends('layouts.admin')

@section('title', 'Don '.$donation->reference)
@section('subtitle', $donation->donor_name)

@section('content')

    <x-admin.page-header :title="'Don '.$donation->reference"
        :subtitle="'Reçu le '.$donation->created_at->translatedFormat('d F Y à H\hi')">
        <x-admin.button variant="secondary" :href="route('admin.donations.index')">Retour à la liste</x-admin.button>
        <x-admin.button :href="route('admin.donations.edit', $donation)" icon="pencil">Modifier</x-admin.button>
    </x-admin.page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Détail du don">
                <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">Montant</dt>
                        <dd class="mt-1 text-2xl font-semibold text-zinc-900">{{ money($donation->amount, true) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">Statut</dt>
                        <dd class="mt-1">
                            <x-admin.badge :color="['completed' => 'green', 'pending' => 'amber', 'failed' => 'red', 'refunded' => 'zinc'][$donation->status]">
                                {{ $donation->status_label }}
                            </x-admin.badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">Moyen de paiement</dt>
                        <dd class="mt-1 text-sm text-zinc-800">{{ $donation->method_label }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">Cause soutenue</dt>
                        <dd class="mt-1 text-sm text-zinc-800">
                            @if ($donation->cause)
                                <a href="{{ route('admin.causes.edit', $donation->cause) }}" class="hover:underline">{{ $donation->cause->title }}</a>
                            @else
                                Fonds libre
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">Encaissé le</dt>
                        <dd class="mt-1 text-sm text-zinc-800">
                            {{ $donation->paid_at?->translatedFormat('d F Y à H\hi') ?? 'Non encaissé' }}
                        </dd>
                    </div>
                    @if ($donation->fund_type)
                        <div class="sm:col-span-2">
                            <dt class="text-xs uppercase tracking-wide text-zinc-500">Intitulé libre</dt>
                            <dd class="mt-1 text-sm text-zinc-800">{{ $donation->fund_type }}</dd>
                        </div>
                    @endif
                    @if ($donation->message)
                        <div class="sm:col-span-2">
                            <dt class="text-xs uppercase tracking-wide text-zinc-500">Message du donateur</dt>
                            <dd class="mt-1 rounded-lg bg-zinc-50 px-4 py-3 text-sm text-zinc-700">{{ $donation->message }}</dd>
                        </div>
                    @endif
                </dl>
            </x-admin.card>

            @if ($history->isNotEmpty())
                <x-admin.card title="Autres dons de ce donateur" :padded="false">
                    <x-admin.table :head="['Référence', 'Montant' => 'right', 'Statut', 'Date']">
                        @foreach ($history as $item)
                            <tr class="hover:bg-zinc-50">
                                <td class="px-5 py-3">
                                    <a href="{{ route('admin.donations.show', $item) }}"
                                        class="font-mono text-xs text-zinc-900 hover:underline">{{ $item->reference }}</a>
                                </td>
                                <td class="px-5 py-3 text-right font-medium tabular-nums">{{ money($item->amount, true) }}</td>
                                <td class="px-5 py-3">
                                    <x-admin.badge :color="['completed' => 'green', 'pending' => 'amber', 'failed' => 'red', 'refunded' => 'zinc'][$item->status]">
                                        {{ $item->status_label }}
                                    </x-admin.badge>
                                </td>
                                <td class="px-5 py-3 text-zinc-500">{{ $item->created_at->translatedFormat('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </x-admin.table>
                </x-admin.card>
            @endif
        </div>

        <div class="space-y-6">
            <x-admin.card title="Donateur">
                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">Nom</dt>
                        <dd class="mt-0.5 font-medium text-zinc-900">{{ $donation->donor_name }}</dd>
                        @if ($donation->is_anonymous)
                            <dd class="mt-1"><x-admin.badge color="amber">Anonyme sur le site</x-admin.badge></dd>
                        @endif
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-zinc-500">E-mail</dt>
                        <dd class="mt-0.5"><a href="mailto:{{ $donation->donor_email }}" class="text-zinc-800 hover:underline">{{ $donation->donor_email }}</a></dd>
                    </div>
                    @if ($donation->donor_phone)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-zinc-500">Téléphone</dt>
                            <dd class="mt-0.5 text-zinc-800">{{ $donation->donor_phone }}</dd>
                        </div>
                    @endif
                    @if ($donation->donor_address)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-zinc-500">Adresse</dt>
                            <dd class="mt-0.5 text-zinc-800">{{ $donation->donor_address }}</dd>
                        </div>
                    @endif
                </dl>
            </x-admin.card>

            <x-admin.card title="Changer le statut">
                <form action="{{ route('admin.donations.status', $donation) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <x-admin.field name="status" label="Statut du paiement" type="select"
                        :value="$donation->status" :options="\App\Models\Donation::STATUSES" required />
                    <p class="text-xs text-zinc-500">
                        Passer le don en « Validé » l'inclut dans le montant collecté de la cause et dans les statistiques.
                    </p>
                    <x-admin.button type="submit" icon="check">Appliquer</x-admin.button>
                </form>
            </x-admin.card>

            <x-admin.card title="Zone sensible">
                <p class="mb-3 text-sm text-zinc-500">La suppression est définitive et recalcule la collecte de la cause.</p>
                <x-admin.delete-form :action="route('admin.donations.destroy', $donation)"
                    label="Supprimer ce don"
                    confirm="Supprimer définitivement le don {{ $donation->reference }} ?" />
            </x-admin.card>
        </div>
    </div>

@endsection
