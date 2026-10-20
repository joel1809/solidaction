@extends('layouts.admin')

@section('title', 'Questions fréquentes')
@section('subtitle', 'Réponses affichées sur la page FAQ')

@section('content')

    <x-admin.page-header title="Questions fréquentes" :subtitle="$faqs->total().' question(s)'">
        <x-admin.button icon="plus" :href="route('admin.faqs.create')">Nouvelle question</x-admin.button>
    </x-admin.page-header>

    <x-admin.card :padded="false">
        @if ($faqs->isEmpty())
            <x-admin.empty title="Aucune question" message="Ajoutez les questions que vos donateurs posent le plus souvent.">
                <x-admin.button icon="plus" :href="route('admin.faqs.create')">Nouvelle question</x-admin.button>
            </x-admin.empty>
        @else
            <x-admin.table :head="['Question', 'Groupe', 'Ordre' => 'center', 'Statut', 'Actions' => 'right']">
                @foreach ($faqs as $faq)
                    <tr class="hover:bg-zinc-50">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.faqs.edit', $faq) }}"
                                class="font-medium text-zinc-900 hover:underline">{{ $faq->question }}</a>
                            <p class="mt-0.5 text-xs text-zinc-500">{{ Str::limit($faq->answer, 110) }}</p>
                        </td>
                        <td class="px-5 py-3 text-zinc-600">{{ $faq->category ?: 'Général' }}</td>
                        <td class="px-5 py-3 text-center tabular-nums text-zinc-500">{{ $faq->sort_order }}</td>
                        <td class="px-5 py-3">
                            <x-admin.badge :color="$faq->is_active ? 'green' : 'zinc'">
                                {{ $faq->is_active ? 'Publiée' : 'Masquée' }}
                            </x-admin.badge>
                        </td>
                        <td class="px-5 py-3">
                            <x-admin.row-actions :edit="route('admin.faqs.edit', $faq)"
                                :destroy="route('admin.faqs.destroy', $faq)" />
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-admin.card>

    <x-admin.pagination :paginator="$faqs" label="question" />

@endsection
