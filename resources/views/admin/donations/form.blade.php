@extends('layouts.admin')

@section('title', $donation->exists ? 'Modifier un don' : 'Saisir un don')
@section('subtitle', $donation->exists ? $donation->reference : 'Don reçu par chèque, virement ou espèces')

@section('content')

    <x-admin.page-header :title="$donation->exists ? 'Modifier le don '.$donation->reference : 'Saisir un don'"
        subtitle="Utilisez ce formulaire pour enregistrer un don reçu hors du site.">
        <x-admin.button variant="secondary" :href="route('admin.donations.index')">Retour à la liste</x-admin.button>
    </x-admin.page-header>

    <form action="{{ $donation->exists ? route('admin.donations.update', $donation) : route('admin.donations.store') }}"
        method="POST" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($donation->exists)
            @method('PUT')
        @endif

        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Donateur">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.field name="donor_name" label="Nom et prénom" :model="$donation" required />
                    <x-admin.field name="donor_email" label="Adresse e-mail" type="email" :model="$donation" required />
                    <x-admin.field name="donor_phone" label="Téléphone" :model="$donation" />
                    <x-admin.field name="donor_address" label="Adresse postale" :model="$donation" />
                    <div class="sm:col-span-2">
                        <x-admin.field name="is_anonymous" type="checkbox" label="Don anonyme"
                            :model="$donation" help="Le nom du donateur ne sera pas affiché publiquement." />
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card title="Message">
                <x-admin.field name="message" label="Message du donateur" type="textarea" rows="4" :model="$donation" />
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Don">
                <div class="space-y-5">
                    <x-admin.field name="amount" label="Montant ({{ setting('currency_symbol', 'FCFA') }})"
                        type="number" step="0.01" min="0.01" :model="$donation" required />

                    <x-admin.field name="cause_id" label="Cause soutenue" type="select" :model="$donation"
                        :options="$causes->all()" placeholder="Fonds libre" />

                    <x-admin.field name="frequency" label="Fréquence" type="select" :model="$donation" required
                        :options="\App\Models\Donation::FREQUENCIES" />

                    <x-admin.field name="payment_method" label="Moyen de paiement" type="select" :model="$donation"
                        required :options="\App\Models\Donation::METHODS" />

                    <x-admin.field name="status" label="Statut" type="select" :model="$donation" required
                        :options="\App\Models\Donation::STATUSES" />

                    <x-admin.field name="paid_at" label="Date d'encaissement" type="datetime-local"
                        :value="old('paid_at', $donation->paid_at?->format('Y-m-d\TH:i'))"
                        help="Renseignée automatiquement lors de la validation si elle est vide." />

                    <x-admin.field name="fund_type" label="Intitulé libre" :model="$donation"
                        placeholder="Ex. : en mémoire de…" />
                </div>
            </x-admin.card>

            <div class="flex items-center gap-3">
                <x-admin.button type="submit" icon="check">
                    {{ $donation->exists ? 'Enregistrer' : 'Créer le don' }}
                </x-admin.button>
                <x-admin.button variant="ghost" :href="route('admin.donations.index')">Annuler</x-admin.button>
            </div>
        </div>
    </form>

@endsection
