@extends('layouts.admin')

@section('title', 'Dons')
@section('subtitle', 'Suivi des promesses et des encaissements')

@section('content')

    <x-admin.page-header title="Dons" subtitle="Validez les paiements et exportez la liste pour la comptabilité.">
        <x-admin.button variant="secondary" icon="download"
            :href="route('admin.donations.export', request()->query())">Exporter en CSV</x-admin.button>
        <x-admin.button icon="plus" :href="route('admin.donations.create')">Saisir un don</x-admin.button>
    </x-admin.page-header>

    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        <x-admin.stat label="Total encaissé" :value="money($totals['completed'])" icon="euro" />
        <x-admin.stat label="En attente de validation" :value="money($totals['pending'])" icon="trend" />
        <x-admin.stat label="Sélection filtrée" :value="money($totals['filtered'])" icon="check"
            :hint="$donations->total().' don(s)'" />
    </div>

    <x-admin.filters :action="route('admin.donations.index')" placeholder="Nom, e-mail ou référence…" dates
        :selects="[
            'status' => ['label' => 'Statut', 'options' => \App\Models\Donation::STATUSES],
            'frequency' => ['label' => 'Fréquence', 'options' => \App\Models\Donation::FREQUENCIES],
            'cause' => ['label' => 'Cause', 'options' => $causes->all(), 'placeholder' => 'Toutes'],
        ]" />

    <x-admin.card :padded="false">
        @if ($donations->isEmpty())
            <x-admin.empty title="Aucun don" message="Aucun don ne correspond à ces critères.">
                <x-admin.button icon="plus" :href="route('admin.donations.create')">Saisir un don</x-admin.button>
            </x-admin.empty>
        @else
            <x-admin.table :head="['Référence', 'Donateur', 'Cause', 'Montant' => 'right', 'Fréquence', 'Statut', 'Date', 'Actions' => 'right']">
                @foreach ($donations as $donation)
                    <tr class="hover:bg-zinc-50">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.donations.show', $donation) }}"
                                class="font-mono text-xs font-medium text-zinc-900 hover:underline">{{ $donation->reference }}</a>
                        </td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-zinc-900">{{ $donation->display_name }}</p>
                            <p class="text-xs text-zinc-500">{{ $donation->donor_email }}</p>
                        </td>
                        <td class="px-5 py-3 text-zinc-600">{{ Str::limit($donation->cause->title ?? 'Fonds libre', 26) }}</td>
                        <td class="px-5 py-3 text-right font-semibold tabular-nums text-zinc-900">{{ money($donation->amount, true) }}</td>
                        <td class="px-5 py-3 text-zinc-600">{{ $donation->frequency_label }}</td>
                        <td class="px-5 py-3">
                            <x-admin.status-form :action="route('admin.donations.status', $donation)"
                                :options="\App\Models\Donation::STATUSES" :current="$donation->status" />
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-zinc-500">{{ $donation->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <x-admin.row-actions :view="route('admin.donations.show', $donation)"
                                :edit="route('admin.donations.edit', $donation)"
                                :destroy="route('admin.donations.destroy', $donation)"
                                confirm="Supprimer le don {{ $donation->reference }} ? Le montant collecté de la cause sera recalculé." />
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-admin.card>

    <x-admin.pagination :paginator="$donations" label="don" />

@endsection
