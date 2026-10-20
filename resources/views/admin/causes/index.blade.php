@extends('layouts.admin')

@section('title', 'Causes')
@section('subtitle', 'Projets de collecte publiés sur le site')

@section('content')

    <x-admin.page-header title="Causes" :subtitle="$causes->total().' projet(s) enregistré(s)'">
        <x-admin.button icon="plus" :href="route('admin.causes.create')">Nouvelle cause</x-admin.button>
    </x-admin.page-header>

    <x-admin.filters :action="route('admin.causes.index')" placeholder="Titre de la cause…" :selects="[
        'status' => ['label' => 'Statut', 'options' => ['draft' => 'Brouillon', 'published' => 'Publiée', 'closed' => 'Clôturée']],
        'category' => ['label' => 'Programme', 'options' => $categories->all()],
    ]" />

    <x-admin.card :padded="false">
        @if ($causes->isEmpty())
            <x-admin.empty title="Aucune cause" message="Créez votre première collecte pour l'afficher sur le site.">
                <x-admin.button icon="plus" :href="route('admin.causes.create')">Nouvelle cause</x-admin.button>
            </x-admin.empty>
        @else
            <x-admin.table :head="['Cause', 'Programme', 'Progression' => 'left', 'Dons' => 'center', 'Statut', 'Actions' => 'right']">
                @foreach ($causes as $cause)
                    <tr class="hover:bg-zinc-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ media($cause->image) }}" alt=""
                                    class="size-11 shrink-0 rounded-lg object-cover">
                                <div class="min-w-0">
                                    <a href="{{ route('admin.causes.edit', $cause) }}"
                                        class="block truncate font-medium text-zinc-900 hover:underline">{{ $cause->title }}</a>
                                    <p class="text-xs text-zinc-500">
                                        {{ $cause->location ?: 'Lieu non précisé' }}
                                        @if ($cause->is_featured)
                                            &middot; <span class="font-medium text-rose-600">à la une</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-zinc-600">{{ $cause->category->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <div class="w-40">
                                <div class="flex items-baseline justify-between text-xs">
                                    <span class="font-semibold text-zinc-900">{{ money($cause->raised_amount) }}</span>
                                    <span class="text-zinc-500">/ {{ money($cause->goal_amount) }}</span>
                                </div>
                                <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-zinc-100">
                                    <div class="h-full rounded-full bg-[#e11d48]" style="width: {{ $cause->progress }}%"></div>
                                </div>
                                <p class="mt-1 text-[11px] text-zinc-500">{{ $cause->progress }} % de l'objectif</p>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-center tabular-nums text-zinc-600">{{ $cause->donations_count }}</td>
                        <td class="px-5 py-3">
                            <x-admin.badge :color="['published' => 'green', 'draft' => 'amber', 'closed' => 'zinc'][$cause->status]">
                                {{ ['published' => 'Publiée', 'draft' => 'Brouillon', 'closed' => 'Clôturée'][$cause->status] }}
                            </x-admin.badge>
                        </td>
                        <td class="px-5 py-3">
                            <x-admin.row-actions :preview="route('causes.show', $cause)" :edit="route('admin.causes.edit', $cause)"
                                :destroy="route('admin.causes.destroy', $cause)"
                                confirm="Supprimer la cause « {{ $cause->title }} » ? Les dons déjà reçus seront conservés mais détachés." />
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-admin.card>

    <x-admin.pagination :paginator="$causes" label="cause" />

@endsection
